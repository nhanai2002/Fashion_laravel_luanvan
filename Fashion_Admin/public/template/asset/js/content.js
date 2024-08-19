
//Hiển thị trang DASHBOARD
function dashboard() {
const subject = document.getElementById('content-subject');
const content = document.getElementById('content-dashboard');
  if(content)
    content.style.display = 'block';
  subject.innerHTML='Dashboard';
  document.getElementById('content-edit').style.display = 'none';
  document.getElementById('content-product').style.display = 'none';
  document.getElementById('content-order').style.display = 'none';
  document.getElementById('content-customer').style.display = 'none';
  document.getElementById('content-rev').style.display = 'none';
  document.getElementById('content-decen').style.display = 'none';

};


// show menu con bên sidebar
document.addEventListener('DOMContentLoaded', function() {
  var arrows = document.querySelectorAll('.fa-angle-down');
  arrows.forEach(function(arrow) {
      arrow.addEventListener('click', function(event) {
          event.preventDefault(); 
          var subItem = this.parentElement.nextElementSibling;
          if (this.getAttribute('data-toggle') === 'closed') {
              subItem.style.display = 'block'; // Hiển thị menu con
              this.setAttribute('data-toggle', 'open');
          } else {
              subItem.style.display = 'none'; // Ẩn menu con
              this.setAttribute('data-toggle', 'closed');
          }
      });
  });
});

//Hiển thị trang PRODUCT

function product() {
const subject = document.getElementById('content-subject');
const content = document.getElementById('content-product');
    if(content)
      content.style.display = 'block';
    subject.innerHTML='Product';
    document.getElementById('content-edit').style.display = 'none';
    document.getElementById('content-dashboard').style.display = 'none';
    document.getElementById('content-order').style.display = 'none';
    document.getElementById('content-customer').style.display = 'none';
    document.getElementById('content-rev').style.display = 'none';
    document.getElementById('content-decen').style.display = 'none';
};
//Hiển thị trang ORDER
function order() {
  const content = document.getElementById('content-order');
  const subject = document.getElementById('content-subject');
 
  if(content)
    content.style.display = 'block';
  subject.innerHTML='Order list';
  document.getElementById('content-edit').style.display = 'none';
  document.getElementById('content-dashboard').style.display = 'none';
  document.getElementById('content-product').style.display = 'none';
  document.getElementById('content-customer').style.display = 'none';
  document.getElementById('content-rev').style.display = 'none';
  document.getElementById('content-decen').style.display = 'none';
};
//Hiển thị trang CUSTOMER
function customer() {
  const content = document.getElementById('content-customer');
  const subject = document.getElementById('content-subject');
 
  if(content)
    content.style.display = 'block';
  subject.innerHTML='Customer list';
  document.getElementById('content-edit').style.display = 'none';
  document.getElementById('content-dashboard').style.display = 'none';
  document.getElementById('content-product').style.display = 'none';
  document.getElementById('content-order').style.display = 'none';
  document.getElementById('content-rev').style.display = 'none';
  document.getElementById('content-decen').style.display = 'none';
};
//Hiển thị trang REVENUE
function reve() {
  const content = document.getElementById('content-rev');
  const subject = document.getElementById('content-subject');
 
  if(content)
    content.style.display = 'block';
  subject.innerHTML='Revenue';
  document.getElementById('content-edit').style.display = 'none';
  document.getElementById('content-dashboard').style.display = 'none';
  document.getElementById('content-product').style.display = 'none';
  document.getElementById('content-order').style.display = 'none';
  document.getElementById('content-customer').style.display = 'none';
  document.getElementById('content-decen').style.display = 'none';
};
//Hiển thị trang DECENTRALIZATION
function decen() {
  const content = document.getElementById('content-decen');
  const subject = document.getElementById('content-subject');
 
  if(content)
    content.style.display = 'block';
  subject.innerHTML='Administrator';
  document.getElementById('content-edit').style.display = 'none';
  document.getElementById('content-dashboard').style.display = 'none';
  document.getElementById('content-product').style.display = 'none';
  document.getElementById('content-order').style.display = 'none';
  document.getElementById('content-customer').style.display = 'none';
  document.getElementById('content-rev').style.display = 'none';
  
};
//Hiển thị thêm danh mục
function add_cate(){
  const cate = document.getElementById('category');
  const tab_cate = document.getElementById('tab-Category'); 
  const pro = document.getElementById('content');
  const subject = document.getElementById('content-subject');
  const btncate = document.getElementById('btn-cate');
  const btnpro = document.getElementById('btn-pro');
  const body =  document.body;
  body.style.backgroundColor='rgba(255, 255, 255, 0.5)';
  cate.style.display="flex";
  tab_cate.style.backgroundColor="white";
  subject.innerHTML='Product > Add category';
  btncate.style.display="none";
  btnpro.style.display="none";
}
function add_product(){
  const cate = document.getElementById('add-product');
  const tab_cate = document.getElementById('tab-Category'); 
  const subject = document.getElementById('content-subject');
  const product_body = document.getElementById('pro-body');
  const btncate = document.getElementById('btn-cate');
  const btnpro = document.getElementById('btn-pro');


  subject.innerHTML='Product > Add product';
  cate.style.display="flex";
  product_body.style.display="none";
  btncate.style.display="none";
  btnpro.style.display="none";
  tab_cate.style.backgroundColor="white";
}
//Ẩn tác vụ 
function cancel(){
  const cancel_cate = document.getElementById('category');
  const cancel_pro = document.getElementById('add-product');
  const subject = document.getElementById('content-subject');
  const btncate = document.getElementById('btn-cate');
  const btnpro = document.getElementById('btn-pro');
  const product_body = document.getElementById('pro-body');
  
  const cancel_add_user = document.getElementById('add-user');

  if(cancel_add_user){
    cancel_add_user.style.display="none";
    document.getElementById('decen-body').style.display = 'block';
    subject.innerHTML='Administrator';

  }

  if(cancel_pro ){
    cancel_pro.style.display="none";
    btncate.style.display="inline";
    btnpro.style.display="inline";
    product_body.style.display="flex";
    subject.innerHTML='Product';
  }
  if(cancel_cate){
    cancel_cate.style.display="none";
    btncate.style.display="inline";
    btnpro.style.display="inline";
    subject.innerHTML='Product';
  }
}
function edit(){
  const content = document.getElementById('content-edit');
  const subject = document.getElementById('content-subject');

  if(content)
    content.style.display="block";
    subject.innerHTML='Edit profile';
  document.getElementById('content-dashboard').style.display = 'none';
  document.getElementById('content-product').style.display = 'none';
  document.getElementById('content-order').style.display = 'none';
  document.getElementById('content-customer').style.display = 'none';
  document.getElementById('content-rev').style.display = 'none';
  document.getElementById('content-decen').style.display = 'none';
}
function add_user(){
  const add = document.getElementById('add-user');
  const subject = document.getElementById('content-subject');
  if(add){
    add.style.display="block";
    subject.innerHTML='Add user';
    document.getElementById('decen-body').style.display = 'none';
  }
}
