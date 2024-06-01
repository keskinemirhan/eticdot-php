<!DOCTYPE html>
<html lang="en">

<head>
  <title>Eticdot - Login</title>
  <?php include "views/_headcontent.php" ?>

</head>

<body>
  <?php include "views/_navbar.php" ?>

  <main style="min-height: 100vh;">
    <div class="container mx-auto">
      <div class="fav-header">
        <h1>Favorites</h1>
      </div>
      <div class="fav-items"></div>
    </div>
    <style>
      .fav-items {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
      }

      .fav-header h1 {
        text-align: center;
        font-size: 32px;
        margin: 20px;
      }

      .empty-favorites {
        text-align: center;
        font-size: 24px;
        font-weight: bold;
        margin-top: 40px;
      }
    </style>

    <script is:inline>
      let favItems = JSON.parse(localStorage.getItem("favItems"));
      if (!favItems) favItems = [];

      function updateFavCards() {
        const docFavItems = document.querySelector(".fav-items");
        if (favItems.length === 0) {
          docFavItems.innerHTML = `<div class="text-dark-grey empty-favorites">No Items...</div>`;
        }
        favItems.forEach((itm) => {
          const {
            price,
            prevPrice,
            prodId,
            imageUrl,
            vendorName,
            prodName
          } = itm;
          docFavItems.innerHTML =
            docFavItems.innerHTML +
            `<a href="/product/${itm.prodId}" class="prod-card">
  <div class="price-band bg-blue">
    <div class="price">
      <div class="current-price">${price + "$"}</div>
      ${prevPrice ? `<div class="prev-price">${prevPrice + "$"}</div>` : ""}
    </div>

    ${
      prevPrice
        ? `<div class="sale bg-green">
          ${
            Math.ceil(
              ((Number(prevPrice) - Number(price)) / Number(prevPrice)) * 100
            ) + "% OFF"
          }
        </div>
      `
        : ""
    }
  </div>
  <div class="fav-btn-box">
    <button data-item='${JSON.stringify(
      itm
    )}' class="${`fav-btn fav-btn-${prodId}`}"
      ><i class="bi bi-heart"></i></button
    >
  </div>
  <div class="prod-img-wrapper">
    <img class="prod-img" src="${imageUrl}" alt="" srcset="" />
  </div>
  <div class="prod-name-wrapper">
    <span class="vendor-name text-blue">${vendorName}</span>
    <span class="prod-name">${prodName}</span>
  </div>
  <div class="prod-rating text-blue">
    <i class="bi bi-star-fill"></i>
    <i class="bi bi-star-fill"></i>
    <i class="bi bi-star-fill"></i>
    <i class="bi bi-star-half"></i>
    <i class="bi bi-star"></i>
  </div>
  <div class="basket-btn-wrapper">
    <button class="basket-btn bg-green basket-btn-${itm.prodId}">
      <i class="bi bi-basket2-fill "></i> Add To Basket
    </button>
  </div>
</a>`;
        });
        loadFavStatus();
        bindFavButton();
      }
      updateFavCards();
      basketBtnEvent();
      favItems.forEach((itm) => {
        const {
          price,
          prevPrice,
          prodId,
          imageUrl,
          vendorName,
          prodName
        } = itm;

        const basketButton = document.querySelector(`.basket-btn-${itm.prodId}`);
        basketButton.addEventListener("click", (e) => {
          e.stopPropagation();
          e.preventDefault();
          let basketItems = JSON.parse(localStorage.getItem("basketItems"));
          if (!basketItems) basketItems = [];
          const existingIndex = basketItems.findIndex(
            (it) => it.prodId === itm.prodId
          );
          if (existingIndex !== -1) {
            basketItems[existingIndex].count++;
          } else {
            basketItems.push({
              ...itm,
              count: 1
            });
          }
          localStorage.setItem("basketItems", JSON.stringify(basketItems));
          new Toast({
            message: "Added to Basket",
            type: "success",
          });
          updateNavBasket();
        });
      });
    </script>

  </main>

  <?php include "views/_bottomscript.php" ?>
  <script>
    loadFavStatus();
    bindFavButton();
    updateNavBasket();
  </script>

  <?php include "views/_footer.php" ?>
</body>

</html>