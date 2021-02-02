<div class="clsMiniCart" id="minicart">
    <div class="clsInnerCart">
        <div class="clsHead basket">
            <p class="clsCart3">
                Cart<span><span></span> items</span>
            </p>

            <div class="clsSlider">
                <a href="#" class="active">Fresh Farms</a>
            </div>
        </div>

        <div class="clsContent">
            <p class="clsCartEmpty" id="lblCartEmpty" style="display: none;">Your cart is empty</p>
            <p class="clsMinOrderAmt">Fresh farm Min. Order $10</p>
            <a href="checkout" class="clsBtn" id="lnkProceedToCheckout"  style="display: none;">Proceed to Checkout</a>
			<p class="clsMinOrderAmt minicart-min-delivery-msg"><img src="images/delivery_van.png" width="27" align="left" alt="Delivery Van" /> Free Delivery Over $30</p>

            <div class="clsCartProds">
            </div>
        </div>
    </div>
</div>

<div class="clsTemplate">
    <div id="tempMiniCart-Store">
        <a href="javascript:void(0);" onclick="selectMiniStore(this);" data-sid="[SID]">[Name]</a>
    </div>
    <div id="tempMiniCart-Prod">
        <div class="clsCartProd">
            <img src="images/quicly-logo-black.png" alt="[Name1]" />
            <div class="clsDetails">
                <p>[Name2]</p>
                <p>
                    <span class="price">$[Price]</span>
                    <a href="javascript:void(0)" id="lnk_cart_[pid]" onclick="removeMiniCartProd([i],'[Key]')" title="Remove"></a>
                    <span class="setqty" id="qty_cart_[pid]">
                        <a href="javascript:void(0)" onclick="removeQtyMiniCart([i1],'[Key1]')">-</a>
                        <span class="qty">[qty]</span>
                        <a href="javascript:void(0)" onclick="addQtyMiniCart([i2],'[Key2]')">+</a>
                    </span>
                </p>
                <p>[Remarks]</p>
            </div>
        </div>
    </div>
</div>