
<?php

require_once __DIR__ . '/../Data/promotions.php';


// VALIDATE PROMOTIONAL CODE
function validatePromoCode($promoCode) {

    global $promotions;

    $promoCode = strtoupper(trim($promoCode));

    if ($promoCode == "") {
        return false;
    }

    foreach ($promotions as $promotion) {

        if ($promotion['automatic'] == false) {

            if (strtoupper($promotion['code']) == $promoCode) {
                return $promotion;
            }

        }

    }

    return false;

}


// CALCULATE PROMOTION AMOUNT
function calculatePromotionAmount($subtotal, $promotion) {

    $discountAmount = 0;

    if ($promotion['type'] == "percentage") {

        $discountAmount = $subtotal * ($promotion['value'] / 100);

    } elseif ($promotion['type'] == "fixed") {

        $discountAmount = $promotion['value'];

    }

    // Discount cannot exceed the subtotal
    if ($discountAmount > $subtotal) {
        $discountAmount = $subtotal;
    }

    return $discountAmount;

}


// CALCULATE THE HIGHEST ELIGIBLE DISCOUNT
function calculateDiscount($subtotal, $promoCode = "") {

    global $promotions;

    $highestDiscount = 0;
    $discountName = "No Discount";
    $appliedPromoCode = "";
    $message = "";

    $eligibleDiscounts = [];


    // CHECK AUTOMATIC DISCOUNTS
    foreach ($promotions as $promotion) {

        if ($promotion['automatic'] == true) {

            if ($subtotal >= $promotion['minimum_subtotal']) {

                $discountAmount = calculatePromotionAmount(
                    $subtotal,
                    $promotion
                );

                $eligibleDiscounts[] = [
                    "name" => $promotion['name'],
                    "amount" => $discountAmount,
                    "code" => ""
                ];

            }

        }

    }


    // CHECK PROMOTIONAL CODE
    if (trim($promoCode) != "") {

        $validPromo = validatePromoCode($promoCode);

        if ($validPromo !== false) {

            if ($subtotal >= $validPromo['minimum_subtotal']) {

                $discountAmount = calculatePromotionAmount(
                    $subtotal,
                    $validPromo
                );

                $eligibleDiscounts[] = [
                    "name" => $validPromo['name'],
                    "amount" => $discountAmount,
                    "code" => $validPromo['code']
                ];

            } else {

                $message = "Promo code does not meet the minimum purchase requirement.";

            }

        } else {

            $message = "Invalid promotional code.";

        }

    }


    // COMPARE ELIGIBLE DISCOUNTS
    foreach ($eligibleDiscounts as $discount) {

        if ($discount['amount'] > $highestDiscount) {

            $highestDiscount = $discount['amount'];

            $discountName = $discount['name'];

            $appliedPromoCode = $discount['code'];

        }

    }


    // RETURN SELECTED DISCOUNT
    return [
        "discount_amount" => $highestDiscount,
        "discount_name" => $discountName,
        "promo_code" => $appliedPromoCode,
        "message" => $message,
        "eligible_discounts" => $eligibleDiscounts
    ];

}


// CALCULATE FINAL TOTAL
function calculateTotal($subtotal, $discountAmount) {

    $total = $subtotal - $discountAmount;

    if ($total < 0) {
        $total = 0;
    }

    return $total;

}

?>
