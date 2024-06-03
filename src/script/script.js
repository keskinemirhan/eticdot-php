function updateNavBasket() {
  let basketItems = JSON.parse(localStorage.getItem("basketItems"));
  const navIndicators = document.querySelectorAll(".basket-count-nav");

  if (!basketItems) basketItems = [];
  let totalItems = 0;
  basketItems.forEach((itm) => {
    totalItems += Number(itm.count);
  });
  navIndicators.forEach((itm) => {
    itm.innerText = totalItems;
  });
}

function addToBasket(item) {
  let basketItems = JSON.parse(localStorage.getItem(".basketItems") ?? "");
  const existingIndex = basketItems.findIndex(
    (itm) => itm.prodId === item.prodId
  );
  if (existingIndex !== -1) {
    basketItems[existingIndex].count++;
  } else {
    basketItems.push({ ...item, count: 1 });
  }
  localStorage.setItem("basketItems", JSON.stringify(basketItems));
}

async function toggleFavorites(prodId) {
  const form = new FormData();
  form.set("prodId", prodId);

  const response = await fetch("./api-favorite.php", {
    credentials: "include",
    body: form,
    method: "POST",
  });
  const code = response.status;
  if (code == 200) {
    return "added";
  } else if (code == 204) {
    return "removed";
  } else {
    return "error";
  }
}

function bindFavButton() {
  const favButton = document.querySelectorAll(".fav-btn");
  favButton.forEach((btn) => {
    btn.addEventListener("click", (e) => {
      e.stopPropagation();
      e.preventDefault();
      if (btn.dataset.prodid == undefined) {
        window.location.href = "login.php";
      } else {
        btn.setAttribute("disabled", "true");
        toggleFavorites(btn.dataset.prodid)
          .catch((e) => {})
          .then((status) => {
            btn.removeAttribute("disabled");
            if (status == "added") {
              btn.innerHTML = '<i class="bi bi-heart-fill"></i>';
              new Toast({
                message: "Added to favorites",
                type: "success",
              });
            } else if (status == "removed") {
              btn.innerHTML = '<i class="bi bi-heart"></i>';
              new Toast({
                message: "Removed from favorites",
                type: "success",
              });
            } else {
              new Toast({
                message: "Could not add to favorites.",
                type: "danger",
              });
            }
            btn.removeAttribute("disabled");
          });
      }
    });
  });
}

function loadFavStatus() {
  const favButtons = document.querySelectorAll(`.fav-btn`);
  let favItems = JSON.parse(localStorage.getItem("favItems"));
  if (!favItems) favItems = [];

  favButtons.forEach((favButton) => {
    const item = JSON.parse(favButton.dataset.item);
    const existingIndex = favItems.findIndex(
      (itm) => itm.prodId === item.prodId
    );
    if (existingIndex !== -1) {
      favButton.innerHTML = '<i class="bi bi-heart-fill"></i>';
    }
  });
}
function basketBtnEvent() {
  $(".basket-btn").on("click", function (e) {
    e.stopPropagation();
    e.target.innerHTML = `    <div class="spinner-border" role="status">
    <span class="sr-only"></span>
  </div>`;
    setTimeout(() => {
      e.target.innerHTML = ` <i class="bi bi-basket2-fill"></i> Add To Basket`;
    }, 1000);
  });
}
bindFavButton();
