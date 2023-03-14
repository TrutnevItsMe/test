<?php
namespace Intervolga\Custom\Tools;
class PriceUtil
{
	/**
	 * @param float $price
	 * @param float $oldPrice
	 * @param string $currency
	 * @return string
	 */
	public static function getHtml($price, $oldPrice, $currency="RUB")
	{
		$html = "";
		$currencyIcon = static::getCurrencyIconByCode($currency);
		$formattedPrice = number_format($price, 0, ".", " ");
		$formattedOldPrice = number_format($oldPrice, 0, ".", " ");

		if ($price < $oldPrice)
		{
			$discount = $oldPrice-$price;
			$html = <<<HTML
				<table cellpadding="4" class="d-flex justify-content-center mt-2">
					<tbody>
						<tr>
							<td rowspan="2">
								<span style="font-size: 18px;"><b>$formattedPrice $currencyIcon</b></span>
							</td>
							<td>
								<span style="padding-left: 8px; color: #ff9900; font-size: 15px;">
									-$discount $currencyIcon
								</span>
							</td>
						</tr>
						<tr>
							<td style="padding-top:20px;">
								<s style="padding-left: 8px; color: #ccc; font-size: 15px; text-decoration:line-through">$formattedOldPrice $currencyIcon</s>
							</td>
						</tr>
					</tbody>
				</table>
HTML;

		}
		else
		{
			$html = <<<HTML
				<div class="price"
					data-currency="$currency"
					data-value="$price">
						<span class="values_wrapper">
							<span class="price_value">$formattedPrice $currencyIcon</span>
						</span>
				</div>
HTML;
		}

		return $html;
	}

	public static function getCurrencyIconByCode($currencyCode)
	{
		switch (strtoupper($currencyCode))
		{
			case "RUB":
				return "₽";
			default:
				return "";
		}
	}
}