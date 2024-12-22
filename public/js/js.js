document.addEventListener("DOMContentLoaded", () => {
  const nomInput = document.getElementById("nom");
  const prenomInput = document.getElementById("prenom");
  const emailInput = document.getElementById("inputEmail");
  const nomUtilisateurInput = document.getElementById("nom_utilisateur");
  const boutonGenererMotDePasse = document.getElementById("genererMotDePasse");
  const togglePassword = document.getElementById("togglePassword");

  // Génération de l'e-mail automatiquement
  function genererEmail() {
    const nomUtilisateur = nomUtilisateurInput.value.trim();
    if (nomUtilisateur) {
      emailInput.value = `${nomUtilisateur}@peguy.org`;
    } else {
      emailInput.value = '';
    }
  }

  // Génération du nom d'utilisateur
  function remplirNomUtilisateur() {
    let nom = nomInput.value.trim().toLowerCase();
    let prenom = prenomInput.value.trim().toLowerCase();

    if (!nom || !prenom) {
      nomUtilisateurInput.value = '';
      genererEmail(); // Efface l'e-mail si le nom d'utilisateur est vide
      return;
    }

    let nomArray = nom.split(/\s+/).filter(word => word.length >= 3);
    let prenomArray = prenom.split(/\s+/).filter(word => word.length >= 3);

    nom = nomArray.join('');
    prenom = prenomArray.join('');

    let nomUtilisateur = `${prenom}.${nom}`;

    if (nomUtilisateur.length > 20) {
      const maxNomLength = 20 - prenom.length - 1;
      if (maxNomLength > 0) {
        nom = nom.substring(0, maxNomLength);
      } else {
        prenom = prenom.substring(0, 20 - 1);
      }
      nomUtilisateur = `${prenom}.${nom}`;
    }

    nomUtilisateurInput.value = nomUtilisateur;
    genererEmail(); // Met à jour l'e-mail
  }

  // Génération du mot de passe
  function genererMotDePasse() {
    const caracteres = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789!@#$%^&*()_+~`|}{[]:;?><,./-=';
    const inputPassword = document.getElementById("inputPassword");
    let motDePasse = '';
    for (let i = 0; i < 12; i++) {
      const randomIndex = Math.floor(Math.random() * caracteres.length);
      motDePasse += caracteres[randomIndex];
    }
    inputPassword.value = motDePasse;
  }

  // Validation des caractères dans l'e-mail
  function onKeyUpEmail(event) {
    const validCharacters = /^[a-zA-Z0-9._%+-@]*$/;
    if (!validCharacters.test(event.target.value)) {
      event.target.value = event.target.value.replace(/[^a-zA-Z0-9._%+-@]/g, '');
    }
  }

  // Attacher les écouteurs d'événements
  nomInput.addEventListener("input", remplirNomUtilisateur);
  prenomInput.addEventListener("input", remplirNomUtilisateur);
  emailInput.addEventListener("keyup", onKeyUpEmail);
  boutonGenererMotDePasse.addEventListener("click", genererMotDePasse);
  togglePassword.addEventListener("click", () => {
    const inputPassword = document.getElementById("inputPassword");
    const type = inputPassword.type === "text" ? "password" : "text";
    inputPassword.type = type;
    togglePassword.textContent = type === "text" ? "Cacher" : "Montrer";
  });

  // Initialiser
  genererMotDePasse();
});
