function changeDraftAjax(obj, id){
    let wait = BX.showWait(obj);
    BX.ajax({
        url: "/local/templates/.default/components/bitrix/sale.personal.order.list/withDraftStatus/ajax/changeDraftStatus.php",
        method: "POST",
        dataType: 'json',
        data: {
            ID: id
        },
        onsuccess: function (data){
            moveDraftToNeedPay(id);
            $(obj).parent().remove(); // удалить кнопку <<Разместиь заказ>>
            BX.closeWait(this,wait);
        },
        onfailure: function(){
            BX.closeWait(this,wait);
        }
    });

}

function moveDraftToNeedPay(idDraft){
    let draftOrder = $(".<?=$draftStatus?>-" + idDraft);
    $(".order-status-N").after(draftOrder); //Переместить из блока с черновиками
}