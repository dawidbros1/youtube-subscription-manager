function initScroll() {
   const container = document.getElementsByClassName('scroll')[0];
   const step = 1000;
   const innerHeight = window.innerHeight;

   container.style.maxHeight = (window.innerHeight + step) + "px";

   window.addEventListener('scroll', () => {
      var scrollY = window.scrollY;
      var maxHeight = parseInt(container.style.maxHeight);

      if (maxHeight - scrollY - innerHeight < 500) {
         container.style.maxHeight = (maxHeight + step) + "px";;
      }
   })
}