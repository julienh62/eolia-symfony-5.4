'use strict';   
console.log("coucous searchjs");

let input = document.querySelector('#search');

let results = document.querySelector('#result');
//let li = document.createElement('li');
input.addEventListener('change', function(){
// search c'est la route 
// input.value c'est la valeur searchItem
//etape 1 ;  la requete se fait sur l'url  fonction searchseance()
// est envoyé au controller 
    fetch('/search/' + input.value)
//etape 4 on recupere le json de la reponse du controller 
    .then(response => response.json())
    //etape 5 on recupere la reponse qui est transforme par fonction response.json qui transforme en format exploitable pour js
    .then( seances => {
        // je vide reponse
        results.innerHTML = ''
      console.log(seances);
       //je remplis
        seances.forEach( seance => {
            let div = document.createElement('div')
            let date = new Date(seance.datedelaseance);
            div.classList.add('container')
           div.innerHTML = ` 
           <div class="row align-items-start"> 
              <div class ="card">
              <img class="imgsite" src="${ seance.picture }" alt="char à voile">
   
       <div class="card-body">
           <h4 class="card-title">${ seance.name } (${ (seance.price/100).toFixed(2) } €)</h4>
   
           <span class="badge text-bg-secondary">${ seance.categorie.title }</span>
   
                  <p class="card-text">${ seance.shortDescription }</p>
                  <p>Date de la séance:
                  ${ date.toLocaleString() }</p>
                  <a href="/${seance.categorie.slug}/${seance.slug}" class="btn btn-primary btn-lg">Détails</a>
                 <a href="/cart/add/${seance.id}" class="btn btn-success btn-lg">Ajouter au panier</a> 
       </div>
       </div>
           
        </div>`;
           

           results.append(div);

        })
    })
 
})




