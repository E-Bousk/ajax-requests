// Reçoit en paramètre un évenement (car liée à un évenement)
function onClickBtnLike(event){
    // Empêche le comportement normal (changement de page lorsque l'on clique sur le lien)
    event.preventDefault();

    // Récupère l'URL du lien
    // (la valeur de THIS dans une fonction liée à un événement = élément HTML qui déclenche cet événement)
    // (donc ici THIS = le <a href> sur lequel on clique)
    const url = this.href;
    console.log('url => ', url);


    // Récupère le SPAN qui à la classe « span.js-likes » au sein de ce <a></a>
    // (THIS représente, dans cette fonction, tout le lien (<a href="{{ [...] </a>)
    const spanLikeCount = this.querySelector('span.js-likes');
    console.log('spanLikeCount => ', spanLikeCount);

    // Récupère l'icon
    const icon= this.querySelector('i');
    console.log('icon => ', icon);


    // Appelle Symfony via Axios (en GET)
    // avec l'url récupèrée
    axios.get(url)
    // Si tout est OK, une fois la réponse reçue, on la met dans une fonction
    // (avec DATA = ce qui est revenu du serveur)
    .then(function(response) {
        console.log('response => ', response);
        
        // modifie le nombre de 'like' par celui renvoyé par le serveur
        spanLikeCount.textContent = response.data["nombre de likes"];
        console.log('likes => ', response.data["nombre de likes"]);
        
        // Remplace un icone par un autre
        if (icon.classList.contains('fas')) {
            icon.classList.replace('fas', 'far');
        } else {
            icon.classList.replace('far', 'fas');
        }
    })
    // En cas d'erreur
    .catch(function(error) {
        if(error.response.status === 403) {
            window.alert('Connectez-vous pour pouvoir liker un article !');
        } else if(error.response.status === 404) {
            window.alert('Problème d\'URL');
        } else {
            window.alert('Problème !!!');
        }

    });
}

// Selectionne tous les éléments <a> qui possèdent la classe « js-like-link »
document.querySelectorAll('a.js-like-link')
// Boucle dans ce tableau (des liens)
.forEach(function(link){
    // écoute les cliques et lorsque l'on clique dessus, appelle la fonction « onClickBtnLike »
    link.addEventListener('click', onClickBtnLike);   
})
console.table(link);