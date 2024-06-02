<div class="profile-container container mx-auto">
  <h1 class="p-header">Profile</h1>
  <div class="p-wrapper">
    <div class="picname">
      <div class="profile-pic">
        <img src="/profile.png" alt="" srcset="" />
      </div>
      <div class="profile-ns">
        <div class="profile-name"></div>
        <div class="profile-surname"></div>
      </div>
    </div>
    <div class="profile-info">
      <div class="p-info">
        <div class="pi-name">Email</div>
        <div class="pi-content p-email"></div>
      </div>
      <div class="p-info">
        <div class="pi-name">Address</div>
        <div class="pi-content">
          Contoso Ltd 215 E Tasman Dr Po Box 65502 CA 95134 San Jose
        </div>
      </div>
      <div class="p-info">
        <div class="pi-name">Phone Number</div>
        <div class="pi-content">+90 540 400 44 44</div>
      </div>
    </div>
    <div class="d-flex justify-center">
      <button class="auth-submit s-logout">Logout</button>
    </div>
  </div>
</div>
<div class="order-wrapper container mx-auto">
  <div class="order-frame">
    <h2 class="order-title">Orders</h2>
    <div class="orders-list">
      <div class="order-item">
        <div class="order-product-list">
          <div class="order-product">
            <div class="oi-img">
              <img src={products[0].imageUrl} alt="" srcset="" />
            </div>
            <div class="oi-name">{products[0].prodName}</div>
            <div class="oi-count">3 Pieces</div>
            <div class="oi-price">{products[0].price}$</div>
          </div>
          <div class="order-product">
            <div class="oi-img">
              <img src={products[1].imageUrl} alt="" srcset="" />
            </div>
            <div class="oi-name">{products[1].prodName}</div>
            <div class="oi-count">3 Pieces</div>
            <div class="oi-price">{products[1].price}$</div>
          </div>
          <div class="order-product">
            <div class="oi-img">
              <img src={products[2].imageUrl} alt="" srcset="" />
            </div>
            <div class="oi-name">{products[2].prodName}</div>
            <div class="oi-count">3 Pieces</div>
            <div class="oi-price">{products[2].price}$</div>
          </div>
        </div>
        <div class="order-total">Total of 400$</div>
        <div class="order-address">
          <div class="a-header">Shipping Address</div>
          <div class="a-info">
            Contoso Ltd 215 E Tasman Dr Po Box 65502 CA 95134 San Jose
          </div>
        </div>
        <div class="order-address">
          <div class="a-header">Shipping Date</div>
          <div class="a-info">19.07.2019 19:30 AM</div>
        </div>
      </div>
      <div class="order-item">
        <div class="order-product-list">
          <div class="order-product">
            <div class="oi-img">
              <img src={products[0].imageUrl} alt="" srcset="" />
            </div>
            <div class="oi-name">{products[0].prodName}</div>
            <div class="oi-count">3 Pieces</div>
            <div class="oi-price">{products[0].price}$</div>
          </div>
          <div class="order-product">
            <div class="oi-img">
              <img src={products[1].imageUrl} alt="" srcset="" />
            </div>
            <div class="oi-name">{products[1].prodName}</div>
            <div class="oi-count">3 Pieces</div>
            <div class="oi-price">{products[1].price}$</div>
          </div>
          <div class="order-product">
            <div class="oi-img">
              <img src={products[2].imageUrl} alt="" srcset="" />
            </div>
            <div class="oi-name">{products[2].prodName}</div>
            <div class="oi-count">3 Pieces</div>
            <div class="oi-price">{products[2].price}$</div>
          </div>
        </div>
        <div class="order-total">Total of 400$</div>
        <div class="order-address">
          <div class="a-header">Shipping Address</div>
          <div class="a-info">
            Contoso Ltd 215 E Tasman Dr Po Box 65502 CA 95134 San Jose
          </div>
        </div>
        <div class="order-address">
          <div class="a-header">Shipping Date</div>
          <div class="a-info">19.07.2019 19:30 AM</div>
        </div>
      </div>
      <div class="order-item">
        <div class="order-product-list">
          <div class="order-product">
            <div class="oi-img">
              <img src={products[0].imageUrl} alt="" srcset="" />
            </div>
            <div class="oi-name">{products[0].prodName}</div>
            <div class="oi-count">3 Pieces</div>
            <div class="oi-price">{products[0].price}$</div>
          </div>
          <div class="order-product">
            <div class="oi-img">
              <img src={products[1].imageUrl} alt="" srcset="" />
            </div>
            <div class="oi-name">{products[1].prodName}</div>
            <div class="oi-count">3 Pieces</div>
            <div class="oi-price">{products[1].price}$</div>
          </div>
          <div class="order-product">
            <div class="oi-img">
              <img src={products[2].imageUrl} alt="" srcset="" />
            </div>
            <div class="oi-name">{products[2].prodName}</div>
            <div class="oi-count">3 Pieces</div>
            <div class="oi-price">{products[2].price}$</div>
          </div>
        </div>
        <div class="order-total">Total of 400$</div>
        <div class="order-address">
          <div class="a-header">Shipping Address</div>
          <div class="a-info">
            Contoso Ltd 215 E Tasman Dr Po Box 65502 CA 95134 San Jose
          </div>
        </div>
        <div class="order-address">
          <div class="a-header">Shipping Date</div>
          <div class="a-info">19.07.2019 19:30 AM</div>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="container mx-auto"></div>

<style is:inline>
  .order-total {
    border-top: 1px solid #b6b6b6;
    margin: 10px 20px;
    text-align: center;
    padding: 10px;
    font-size: 24px;
    font-weight: bold;
  }

  .order-address {
    display: flex;
    border: 1px solid #b6b6b6;
    margin: 10px;
    border-radius: 20px;
    overflow: hidden;
  }

  .a-header {
    width: 150px;
    padding: 10px;

    background-color: rgb(235, 235, 235);
  }

  .a-info {
    padding: 10px;
  }

  .order-item {
    border: 1px solid #b6b6b6;
    border-radius: 20px;
    margin-top: 20px;
    background-color: #f1f1f1;
  }

  .order-product img {
    width: 80px;
    height: 80px;
    object-fit: contain;
    border-radius: 20px;
    border: 1px solid #b6b6b6;
  }

  .oi-img {
    flex: 1;
  }

  .oi-price {
    flex: 1;
  }

  .oi-count {
    flex: 1;
  }

  .oi-name {
    font-weight: bold;
    flex: 1;
  }

  .order-product {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 10px 20px 10px 20px;
    font-size: 17px;
  }

  .p-wrapper {
    border: 1px solid rgb(189, 189, 189);
    border-radius: 15px;
    max-width: 700px;
    padding: 20px;
    margin-left: auto;
    margin-right: auto;
  }

  .p-header {
    margin-top: 20px;
    text-align: center;
  }

  .p-info {
    display: flex;
    border: 1px solid rgb(201, 201, 201);
    margin-bottom: 10px;
    margin-top: 10px;
    overflow: hidden;
    border-radius: 10px;
    flex-wrap: wrap;
  }

  .pi-content {
    padding: 10px;
  }

  .pi-name {
    width: 150px;
    overflow: hidden;
    font-weight: bold;
    background-color: rgb(235, 235, 235);
    border-right: 1px solid rgb(235, 235, 235);
    padding: 10px;
  }

  .profile-pic img {
    width: 100px;
    border-radius: 20px;
  }

  .picname {
    margin-top: 20px;
    display: flex;
    align-items: center;
    flex-wrap: wrap;
  }

  .profile-ns {
    display: flex;
    justify-content: space-around;
    gap: 10px;
    margin-left: 20px;
    font-size: 24px;
    font-weight: bold;
    text-align: center;
  }

  .profile-surname {
    text-transform: uppercase;
  }
</style>
<script is:inline>
  const logoutBtn = document.querySelector(".s-logout");
  const name = document.querySelector(".profile-name");
  const surname = document.querySelector(".profile-surname");
  const email = document.querySelector(".p-email");
  const loggedUser = JSON.parse(localStorage.getItem("logUser"));
  if (!loggedUser) {
    window.location.replace("login.php");
  } else {
    name.textContent = loggedUser.name;
    surname.textContent = loggedUser.surname;
    email.textContent = loggedUser.email;
  }
  logoutBtn.addEventListener("click", () => {
    localStorage.removeItem("logUser");
    localStorage.removeItem("basketItems");
    localStorage.removeItem("favItems");
    localStorage.removeItem("totalBasket");
    window.location.replace("");
  });
</script>