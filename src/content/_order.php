<div class="order-card-c container mx-auto">
    <div class="order-card mx-auto">
        <div class="order-card-title">
            <i class="bi bi-credit-card-2-front-fill text-blue"></i>
            Payment Card
        </div>
        <div class="card-form">
            <div class="card-holder card-big">
                <label for="card-holder">Card Holder</label>
                <input id="#card-holder" class="auth-input card-wall" type="text" />
            </div>
            <div class="card-number card-big">
                <label for="card-number">Card Number</label>
                <input id="#card-number" class="auth-input card-wall" type="text" />
            </div>

            <div class="card-info">
                <div class="card-info-wr">
                    <label for="card-year">Expire Year</label>
                    <input id="#card-year" class="auth-input card-piece" type="text" />
                </div>
                <div class="card-info-wr">
                    <label for="card-month">Expire Month</label>
                    <input id="#card-month" class="auth-input card-piece" type="text" />
                </div>
                <div class="card-info-wr">
                    <label for="card-cvc">CVC</label>
                    <input id="#card-cvc" class="auth-input card-piece" type="text" />
                </div>
            </div>
        </div>
    </div>
</div>
<div class="order-card-c container mx-auto">
    <div class="order-card mx-auto">
        <div class="order-card-title">
            <i class="bi bi-truck text-blue"></i>
            Address Info
        </div>
        <div class="card-form">
            <div class="card-holder card-big">
                <label for="card-holder">Card Holder</label>
                <input id="#card-holder" class="auth-input card-wall" type="text" />
            </div>
            <div class="card-info">
                <div class="card-info-wr">
                    <label for="card-year">Country</label>
                    <input id="#card-year" class="auth-input card-piece" type="text" />
                </div>
                <div class="card-info-wr">
                    <label for="card-month">City</label>
                    <input id="#card-month" class="auth-input card-piece" type="text" />
                </div>
                <div class="card-info-wr">
                    <label for="card-cvc">Postal Code</label>
                    <input id="#card-cvc" class="auth-input card-piece" type="text" />
                </div>
            </div>
        </div>
    </div>
</div>
<div class="total-container">
    <div class="total-basket">
        <div class="total-price">
            <i class="bi bi-receipt text-blue"></i>
            <div class="m-total-price"></div>
        </div>
        <button class="confirm-order bg-green">Confirm Order <i class="bi bi-check-circle-fill"></i></button>
    </div>
</div>
<style>
    .total-container {
        margin-top: 20px;
    }

    .card-info-wr {
        display: flex;
        align-items: center;
        margin-top: 10px;
    }

    .card-info {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        margin-top: 10px;
    }

    .order-card {
        margin-top: 20px;
        max-width: 700px;
        border: 2px solid grey;
        padding: 10px;
        border-radius: 20px;
    }

    .card-wall {
        flex: 1;
        min-width: 200px;
    }

    .card-piece {
        width: 120px;
    }

    .card-big {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
    }

    label {
        font-weight: bold;
    }

    .order-card-title {
        font-size: 32px;
        font-weight: bold;
        margin-bottom: 10px;
    }
</style>

<script>
    const confirmOrder = document.querySelector(".confirm-order");
    const priceIndd = document.querySelector(".m-total-price");
    priceIndd.textContent = localStorage.getItem("totalBasket") + "$";
    const isLogged = localStorage.getItem("logUser");
    confirmOrder.addEventListener("click", () => {
        window.location.replace("/success.php");
        localStorage.removeItem("basketItems");
        localStorage.removeItem("totalBasket");
    });
    if (!isLogged) {
        window.location.replace("/login.php");
    }
</script>