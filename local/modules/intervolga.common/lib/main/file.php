<? namespace Intervolga\Common\Main;

use Bitrix\Main\Config\Option;

class File extends \CFile
{
    public static function add(array $data)
    {
        if (!$data['SRC'] || !file_exists($data['SRC'])) {
            return false;
        }
        $arFile = static::MakeFileArray($data['SRC']);
        $arFile['description'] = $data['DESCRIPTION'];
        return \CFile::SaveFile($arFile, $data['FOLDER']);
    }

    public static function update($primary, array $data)
    {
        if (!$data['SRC'] || !file_exists($data['SRC'])) {
            return false;
        }

        $arFileOld = static::GetByID($primary)->Fetch();
        
        static::Delete($primary);
        $arFile = array_merge(
            $arFileOld,
            static::MakeFileArray($data['SRC'])
        );

        $strFileName = GetFileName($arFile["name"]);

        $arFile["ORIGINAL_NAME"] = $strFileName;

        if (Option::get("main", "disk_space") > 0) {
            $quota = new \CDiskQuota();
            if (!$quota->checkDiskQuota($arFile)) {
                return false;
            }
        }

        if ($arFile["type"] == "image/pjpeg" || $arFile["type"] == "image/jpg") {
            $arFile["type"] = "image/jpeg";
        }

        $io = \CBXVirtualIo::GetInstance();
        $upload_dir = Option::get("main", "upload_dir", "upload");
        $strSavePath = $arFile["SUBDIR"];
        $strFileName = $arFile["FILE_NAME"];
        $strDirName = $_SERVER["DOCUMENT_ROOT"] . "/" . $upload_dir . "/" . $strSavePath . "/";
        $strDbFileNameX = $strDirName . $strFileName;
        $strPhysicalFileNameX = $io->GetPhysicalName($strDbFileNameX);

        CheckDirPath($strDirName);

        if (is_set($arFile, "content")) {
            $f = fopen($strPhysicalFileNameX, "ab");
            if (!$f) {
                return false;
            }
            if (fwrite($f, $arFile["content"]) === false) {
                return false;
            }
            fclose($f);
        } elseif (
            !copy($arFile["tmp_name"], $strPhysicalFileNameX)
            && !move_uploaded_file($arFile["tmp_name"], $strPhysicalFileNameX)
        ) {
            return false;
        }

        @chmod($strPhysicalFileNameX, BX_FILE_PERMISSIONS);

        //flash is not an image
        $flashEnabled = !static::IsImage($arFile["ORIGINAL_NAME"], $arFile["type"]);

        $imgArray = static::GetImageSize($strDbFileNameX, false, $flashEnabled);

        if (is_array($imgArray)) {
            $arFile["WIDTH"] = $imgArray[0];
            $arFile["HEIGHT"] = $imgArray[1];

            if ($imgArray[2] == IMAGETYPE_JPEG) {
                $exifData = static::ExtractImageExif($strPhysicalFileNameX);
                if ($exifData && isset($exifData['Orientation'])) {
                    //swap width and height
                    if ($exifData['Orientation'] >= 5 && $exifData['Orientation'] <= 8) {
                        $arFile["WIDTH"] = $imgArray[1];
                        $arFile["HEIGHT"] = $imgArray[0];
                    }

                    $properlyOriented = static::ImageHandleOrientation($exifData['Orientation'], $io->GetPhysicalName($strDbFileNameX));
                    if ($properlyOriented) {
                        $jpgQuality = intval(Option::get('main', 'image_resize_quality', '95'));
                        if ($jpgQuality <= 0 || $jpgQuality > 100) {
                            $jpgQuality = 95;
                        }

                        imagejpeg($properlyOriented, $strPhysicalFileNameX, $jpgQuality);
                        clearstatcache(true, $strPhysicalFileNameX);
                        $arFile['size'] = filesize($strPhysicalFileNameX);
                    }
                }
            }
        } else {
            $arFile["WIDTH"] = 0;
            $arFile["HEIGHT"] = 0;
        }

        /****************************** QUOTA ******************************/
        if (intval(Option::get("main", "disk_space")) > 0)
        {
            \CDiskQuota::UpdateDiskQuota("file", $arFile["size"], "insert");
        }
        /****************************** QUOTA ******************************/

        $NEW_IMAGE_ID = static::DoInsert(array(
            "HEIGHT" => $arFile["HEIGHT"],
            "WIDTH" => $arFile["WIDTH"],
            "FILE_SIZE" => $arFile["size"],
            "CONTENT_TYPE" => $arFile["type"],
            "SUBDIR" => $arFile["SUBDIR"],
            "FILE_NAME" => $arFile["FILE_NAME"],
            "MODULE_ID" => $arFile["MODULE_ID"],
            "ORIGINAL_NAME" => $arFile["ORIGINAL_NAME"],
            "DESCRIPTION" => isset($arFile["DESCRIPTION"]) ? $arFile["DESCRIPTION"] : '',
            "HANDLER_ID" => isset($arFile["HANDLER_ID"]) ? $arFile["HANDLER_ID"] : '',
            "EXTERNAL_ID" => isset($arFile["external_id"]) ? $arFile["external_id"] : md5(mt_rand()),
        ));

        \CFile::CleanCache($NEW_IMAGE_ID);

        return $NEW_IMAGE_ID;
    }
}