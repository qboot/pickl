    $(document).ready(function() {
        // script perso
        $('textarea').each(function(){
            var el = $(this).parent().children('.textCount');
            el.html($(this).val().length +'/'+ el.attr('data'));
        });

        $(document).on('keyup','textarea', function(){
            var el = $(this).parent().children('.textCount');
            el.html($(this).val().length +'/'+ el.attr('data'));
        });

        // On récupère la balise <div> en question qui contient l'attribut « data-prototype » qui nous intéresse.

        if($('div#project_collection').length) {
            var $container = $('div#project_collection');

            // On définit un compteur unique pour nommer les champs qu'on va ajouter dynamiquement
            var index = $container.find(':input').length;

            // On ajoute un nouveau champ à chaque clic sur le lien d'ajout.
            $('#add_counterpart').click(function (e) {
                addCounterpart($container);

                e.preventDefault(); // évite qu'un # apparaisse dans l'URL
                return false;
            });

            // On ajoute un premier champ automatiquement s'il n'en existe pas déjà un (cas d'une nouvelle annonce par exemple).
            if (index == 0) {
                //addCounterpart($container);
            } else {
                // S'il existe déjà des catégories, on ajoute un lien de suppression pour chacune d'entre elles
                $container.children('div').each(function () {
                    addDeleteLink($(this));
                });
            }

            // La fonction qui ajoute un formulaire CounterType
            function addCounterpart($container) {
                // Dans le contenu de l'attribut « data-prototype », on remplace :
                // - le texte "__name__label__" qu'il contient par le label du champ
                // - le texte "__name__" qu'il contient par le numéro du champ
                var template = $container.attr('data-prototype')
                    .replace(/__name__label__/g, 'New gift')
                    .replace(/__name__/g, index)
                    ;

                // On crée un objet jquery qui contient ce template
                var $prototype = $(template);

                // On ajoute au prototype un lien pour pouvoir supprimer la catégorie
                addDeleteLink($prototype);

                // On ajoute le prototype modifié à la fin de la balise <div>
                $container.append($prototype);

                // Enfin, on incrémente le compteur pour que le prochain ajout se fasse avec un autre numéro
                index++;
            }

            // La fonction qui ajoute un lien de suppression d'une counterpart
            function addDeleteLink($prototype) {
                // Création du lien
                var $deleteLink = $('<li><a href="#" class="removeCat">Remove this gift</a></li>');

                // Ajout du lien
                $prototype.append($deleteLink);

                // Ajout du listener sur le clic du lien pour effectivement supprimer la counterpart
                $deleteLink.click(function (e) {
                    $prototype.remove();

                    e.preventDefault(); // évite qu'un # apparaisse dans l'URL
                    return false;
                });
            }
        }
    });