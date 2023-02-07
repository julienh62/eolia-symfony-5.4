'use strict';    
    
       
           /* création du tableau */
          const slide = ["char-accueiltitregros1500.webp", "chardepart-1500.webp", "cata.webp", "paddle.webp" ];
          let numero = 0;


 
          function ChangeSlide(sens) {
            /* document , page en cour */
            /* sens est un parametre */
            numero = numero + sens;
            /*les conditions permettent de boucler */
            if (numero > slide.length - 1)
                numero = 0;
            if (numero < 0)
                numero = slide.length - 1;
            document.getElementById("slide").src = "assets/uploads/slide/" + slide[numero];
          }

          setInterval("ChangeSlide(1)", 4000);
