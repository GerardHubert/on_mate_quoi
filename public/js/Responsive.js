class Responsive {

  constructor() {
    this.navbarElements = document.getElementsByClassName('nav-link');
    this.links = document.getElementById("nav-link-wrapper");
    this.menuButton = document.getElementById('menu-button');
    this.menuButton.addEventListener('click', () => {
      this.showMenu()
    });
  }

  showMenu() {
    // récuperer la classlist de la div menu et l'afficher ou la cacher
    console.log(this.links.classList)
    this.links.classList.toggle('invisible');
  }
}

const responsive = new Responsive();