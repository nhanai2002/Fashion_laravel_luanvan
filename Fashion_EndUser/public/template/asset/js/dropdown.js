var btn = document.getElementsByClassName('.btn');
var menu = document.getElementById('menu-drop');
let isVisible = false;

function toggleVisibility() {
  if (isVisible) {
    menu.style.display = 'none';
    isVisible = false;
  } else {
    menu.style.display = 'flex';
    isVisible = true;
  }
}