<!DOCTYPE html>
<html lang="en">

<head>
    <title>Eticdot</title>
    <?php include "views/_headcontent.php" ?>

</head>

<body>
    <?php include "views/_navbar.php" ?>

    <main style="min-height: 100vh;">
        <div class="c-bt">Basket</div>
        <div class="basket-items container mx-auto"></div>
        <div class="total-container d-none">
            <div class="total-basket">
                <div class="total-price">
                    <i class="bi bi-receipt text-blue"></i>
                    <div class="m-total-price">100$</div>
                </div>
                <button class="confirm-basket bg-green">Confirm Basket <i class="bi bi-check-circle-fill"></i></button>
            </div>
        </div>
        <div class="empty-basket">
            <div class="text-dark-grey">No items...</div>
        </div>
        <?php include "views/_bottomscript.php" ?>

        <script>
            function updateTotal() {
                let basketItems = JSON.parse(localStorage.getItem("basketItems"));
                const totalIndicator = document.querySelector(".m-total-price");
                const totalContainer = document.querySelector(".total-container");
                const emptyBasket = document.querySelector(".empty-basket");
                if (!basketItems) basketItems = [];
                let total = 0;
                basketItems.forEach((itm) => {
                    total += Number(itm.price) * Number(itm.count);
                });
                totalIndicator.innerHTML = total + "$";
                localStorage.setItem("totalBasket", total);

                if (total !== 0) {
                    totalContainer.classList.remove("d-none");
                    emptyBasket.classList.add("d-none");
                } else {
                    totalContainer.classList.add("d-none");
                    emptyBasket.classList.remove("d-none");
                }
                updateNavBasket();
            }
            updateTotal();

            function update() {
                let basketItems = JSON.parse(localStorage.getItem("basketItems"));
                const docBasketItems = document.querySelector(".basket-items");
                if (!basketItems) basketItems = [];
                docBasketItems.innerHTML = "";
                basketItems.forEach((itm) => {
                    const {
                        price,
                        prevPrice,
                        prodName,
                        vendorName,
                        imageUrl,
                        prodId,
                        count,
                    } = itm;
                    docBasketItems.innerHTML =
                        docBasketItems.innerHTML +
                        `<div class="b-item-container container mx-auto mt-3">
      <div class="basket-item">
        <div class="img-name">
          <div class="basket-image" style="background-image:url('${imageUrl}'); "  ></div>
          <a href="/product/${prodId}" class="prod-name">
            <span class="prod-vendor text-blue">${vendorName} </span>${prodName}
          </a>
          <div  class="prod-price text-blue">${price}$ <span class="prev-price">${
            prevPrice ? prevPrice + "$" : ""
          }</span></div>
        </div>
        <div class="quantity">
          <button class="prod-plus prod-plus-${prodId} text-green"
            ><i class="bi bi-plus-circle-fill"></i></button
          >
          <div class="prod-quantity">${count}</div>
          <button class="prod-minus prod-minus-${prodId} text-red"
            ><i class="bi bi-dash-circle-fill"></i></button
          >
          <button class="prod-trash prod-trash-${prodId} text-red"
            ><i class="bi bi-trash-fill"></i></button
          >
        </div>
      </div>
    </div>`;
                });
                basketItems.forEach((itm) => {
                    const {
                        price,
                        prevPrice,
                        prodName,
                        vendorName,
                        imageUrl,
                        prodId,
                        count,
                    } = itm;

                    const minusButton = document.querySelector(`.prod-minus-${prodId}`);
                    const plusButton = document.querySelector(`.prod-plus-${prodId}`);
                    const trashButton = document.querySelector(`.prod-trash-${prodId}`);

                    minusButton.addEventListener("click", () => {
                        itm.count--;
                        if (itm.count < 1) basketItems.splice(basketItems.indexOf(itm), 1);
                        localStorage.setItem("basketItems", JSON.stringify(basketItems));
                        update();
                        updateTotal();
                    });
                    plusButton.addEventListener("click", () => {
                        itm.count++;
                        localStorage.setItem("basketItems", JSON.stringify(basketItems));
                        update();
                        updateTotal();
                    });
                    trashButton.addEventListener("click", () => {
                        basketItems.splice(basketItems.indexOf(itm), 1);
                        localStorage.setItem("basketItems", JSON.stringify(basketItems));
                        update();
                        updateTotal();
                    });
                });
            }
            update();
            const confirmButton = document.querySelector(".confirm-basket");
            confirmButton.addEventListener("click", () => {
                const user = JSON.parse(localStorage.getItem("logUser"));
                if (!user) {
                    new Toast({
                        message: "Please login or register to order",
                        type: "danger",
                    });
                } else {
                    window.location.replace("/order.php");
                }
            });
        </script>
    </main>

    <script>
        loadFavStatus();
        bindFavButton();
        updateNavBasket();
    </script>

    <?php include "views/_footer.php" ?>
</body>