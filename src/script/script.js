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

function toggleFavorites(item) {
  let prevItems = JSON.parse(localStorage.getItem("favItems") ?? "[]");
  let flag = true;
  const existingIndex = prevItems.findIndex(
    (itm) => itm.prodId === item.prodId
  );
  if (existingIndex !== -1) {
    prevItems.splice(existingIndex, 1);
    flag = false;
  } else {
    flag = true;
    prevItems.push(item);
  }
  localStorage.setItem("favItems", JSON.stringify(prevItems));
  return flag;
}

function bindFavButton() {
  const favButton = document.querySelectorAll(".fav-btn");
  favButton.forEach((btn) => {
    btn.addEventListener("click", (e) => {
      e.stopPropagation();
      e.preventDefault();
      const item = JSON.parse(btn.dataset.item);
      if (toggleFavorites(item)) {
        btn.innerHTML = '<i class="bi bi-heart-fill"></i>';
      } else {
        btn.innerHTML = '<i class="bi bi-heart"></i>';
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
    e.target.innerHTML = `    <div class="spinner-border" role="status">
    <span class="sr-only"></span>
  </div>`;
    setTimeout(() => {
      e.target.innerHTML = ` <i class="bi bi-basket2-fill"></i> Add To Basket`;
    }, 1000);
  });
}
basketBtnEvent();
updateNavBasket();
