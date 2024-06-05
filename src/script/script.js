function updateNavBasket(count) {
  const basketCountIndicators = document.querySelectorAll(".basket-count-nav");
  basketCountIndicators.forEach((ind) => {
    ind.textContent = count;
  });
}

function updateTotalCount(total) {
  const totalIndicators = document.querySelectorAll(".m-total-price");
  totalIndicators.forEach((ind) => {
    if (total > 0) ind.textContent = total + "$";
    else {
      const container = document.querySelector("main");
      container.innerHTML += `    <div class="empty-basket">
     <div class="text-dark-grey">No items...</div>
 </div>`;
      const totalContainer = document.querySelector(".total-container");
      totalContainer.remove();
    }
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
async function addBasket(prodId) {
  const formData = new FormData();
  formData.set("prodId", prodId);
  formData.set("add", "true");
  const response = await fetch("api-basket.php", {
    method: "POST",
    credentials: "include",
    body: formData,
  });
  return response;
}
async function removeBasket(prodId, whole = false) {
  const formData = new FormData();
  formData.set("prodId", prodId);
  formData.set("delete", "true");
  if (whole) formData.set("whole", "true");
  const response = await fetch("api-basket.php", {
    method: "POST",
    credentials: "include",
    body: formData,
  });
  return response;
}

function bindBasketPageBtns() {
  const plusBtns = document.querySelectorAll(".prod-plus");
  const minusBtns = document.querySelectorAll(".prod-minus");
  const trashBtns = document.querySelectorAll(".prod-trash");
  const action = (transaction, btn, statusErr, statusDone) => {
    btn.addEventListener("click", (e) => {
      e.stopPropagation();
      e.preventDefault();
      btn.setAttribute("disabled", "true");
      const prodId = btn.dataset.prodid;
      transaction(prodId).then(async (res) => {
        const body = await res.json();
        if (res.ok) {
          new Toast({
            message: statusDone,
            type: "success",
          });
          updateTotalCount(body.total);
        } else
          new Toast({
            message: statusErr,
            type: "danger",
          });
        if (body.prodAmount > 0) {
          btn.parentElement.firstChild.nextSibling.nextSibling.nextSibling.textContent =
            body.prodAmount;
          btn.removeAttribute("disabled");
        } else document.querySelector("#id" + prodId).remove();

        updateNavBasket(body.count);
      });
    });
  };
  plusBtns.forEach((btn) => {
    action(
      (prodId) => addBasket(prodId),
      btn,
      "Could not add to basket",
      "Successfully added to basket"
    );
  });
  minusBtns.forEach((btn) => {
    action(
      (prodId) => removeBasket(prodId, false),
      btn,
      "Could not remove from basket .",
      "Successfully removed from basket"
    );
  });
  trashBtns.forEach((btn) => {
    action(
      (prodId) => removeBasket(prodId, true),
      btn,
      "Could not remove from basket .",
      "Successfully removed from basket"
    );
  });
}
function bindBasketButton() {
  const basketBtns = document.querySelectorAll(".basket-btn");
  basketBtns.forEach((btn) => {
    btn.addEventListener("click", (e) => {
      e.stopPropagation();
      e.preventDefault();
      btn.innerHTML = `<div class="spinner-border" role="status">
    <span class="sr-only"></span>
  </div>`;
      const prodId = btn.dataset.prodid;
      if (!prodId) window.location.href = "login.php";
      addBasket(prodId).then(async (res) => {
        if (res.ok) {
          new Toast({
            message: "Product added to basket.",
            type: "success",
          });
          const body = await res.json();
          updateNavBasket(body.count);
        } else
          new Toast({
            message: "Could not add product to basket.",
            type: "danger",
          });
        setTimeout(() => {
          btn.innerHTML = ` <i class="bi bi-basket2-fill"></i> Add To Basket`;
        }, 500);
      });
    });
  });
}
bindBasketButton();
bindBasketPageBtns();
bindFavButton();
