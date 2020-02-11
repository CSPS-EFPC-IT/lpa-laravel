<?php

return [
    'form_title' => 'Détails opérationnels',
    'tabs'       => [
        'instructors'      => 'Instructeurs',
        'guest_speakers'   => 'Conférenciers invités',
        'offering_details' => 'Détails de l\'offre',
        'rooms'            => 'Salles',
        'materials'        => 'Fournitures',
        'documents'        => 'Documents',
        'gc_campus'        => 'GCcampus',
        'comments'         => 'Commentaires',
    ],
    'form_section_groups' => [
        'instructor' => [
            'label'       => 'Instructeur|Instructeurs',
            'instruction' => 'Veuillez fournir les informations pour chaque instructeur requis en utilisant les contrôles ci-dessous. Utilisez le bouton + pour ajouter une autre entrée à la liste.',
            'description' => 'La description des profiles d\'instructeur nécessaires pour la prestation d\'une offre de ce produit d\'apprentissage.',
        ],
        'guest_speaker' => [
            'label'       => 'Conférencier invité|Conférenciers invités',
            'instruction' => 'Veuillez fournir les informations pour chaque conférencier invité requis en utilisant les contrôles ci-dessous. Utilisez le bouton + pour ajouter une autre entrée à la liste.',
            'description' => 'La description des profiles de conférencier invité nécessaires pour la prestation d\'une offre de ce produit d\'apprentissage.',
            'help'        => 'Les conférenciers invités sont également connus en tant qu\'experts en la matière.',
        ],
        'room' => [
            'label'       => 'Salle|Salles',
            'instruction' => 'Veuillez fournir les informations pour chaque salle requise en utilisant les contrôles ci-dessous. Utilisez le bouton + pour ajouter une autre entrée à la liste.',
            'description' => 'La description des salles nécessaires à la prestation d\'une offre de ce produit d\'apprentissage.',
        ],
        'material' => [
            'label'       => 'Fourniture|Fournitures',
            'instruction' => 'Veuillez fournir les informations pour chaque item requis en utilisant les contrôles ci-dessous. Utilisez le bouton + pour ajouter une autre entrée à la liste.',
            'description' => 'La description des fournitures nécessaires à la prestation d\'une offre de ce produit d\'apprentissage.',
        ],
        'document' => [
            'label'       => 'Document|Documents',
            'instruction' => 'Veuillez fournir les informations pour chaque document requis en utilisant les contrôles ci-dessous. Utilisez le bouton + pour ajouter une autre entrée à la liste.',
            'description' => 'La description des documents nécessaires à la prestation d\'une offre de ce produit d\'apprentissage.',
        ],
    ],
    'instructors' => [
        'required_profile' => [
            'label'       => 'Profil requis',
            'instruction' => 'Veuillez fournir les descriptions en anglais et en français des qualifications, des compétences et des qualités personnelles requises de l\'instructeur du cours.',
            'description' => 'Les descriptions en anglais et en français des exigences relatives au profil de l\'instructeur.',
        ],
        'schedule' => [
            'label'       => 'Horaire',
            'instruction' => 'Veuillez fournir les descriptions en anglais et en français de l\'horaire de l\'instructeur.',
            'description' => 'Les descriptions en anglais et en français de l\'horaire de l’instructeur.',
            'help'        => 'Par example : « Jour 2 seulement », « Jours 1 et 3 », « Jour 1, après-midi seulement ».',
        ],
    ],
    'guest_speakers' => [
        'required_profile' => [
            'label'       => 'Profil requis',
            'instruction' => 'Veuillez fournir les descriptions en anglais et en français des qualifications, des compétences et des qualités personnelles requises du conférencier invité.',
            'description' => 'Les descriptions en anglais et en français des exigences relatives au profil du conférencier invité.',
        ],
        'schedule' => [
            'label'       => 'Horaire',
            'instruction' => 'Veuillez fournir les descriptions en anglais et en français de l\'horaire du conférencier invité.',
            'description' => 'Les descriptions en anglais et en français de l\'horaire du conférencier invité.',
            'help'        => 'Par example : « Jour 2 seulement », « Jours 1 et 3 », « Jour 1, après-midi seulement ».',
        ],
    ],
    'number_of_virtual_producers_per_offering' => [
        'label'       => 'Nombre de producteurs virtuels par offre',
        'description' => 'Le nombre de producteurs virtuels requis par offre.',
        'help'        => 'Ce control est activé seulement lorsque les types d\'offres possibles au niveau du formulaire de conception incluent « Virtuelle » ou « En personne et virtuelle simultanément ».',
    ],
    'minimum_number_of_participant_per_offering' => [
        'label'       => 'Nombre minimum de participants par offre',
        'description' => 'Le nombre minimum de participants par offre pour offir ce produit d\'apprentissage avec succès.',
    ],
    'maximum_number_of_participant_per_offering' => [
        'label'       => 'Nombre maximum de participants par offre',
        'description' => 'Le nombre maximum de participants par offre pour offir ce produit d\'apprentissage avec succès.',
    ],
    'optimal_number_of_participant_per_offering' => [
        'label'       => 'Nombre optimal de participants par offre',
        'description' => 'Le nombre optimal de participants par offre pour offir ce produit d\'apprentissage avec succès.',
    ],
    'waiting_list_maximum_size' => [
        'label'       => 'Nombre maximum de participants par liste d\'attente',
        'description' => 'Le nombre maximum de participants permis sur la liste d\'attente par offre.',
    ],
    'session_template' => [
        'label'       => 'Modèle de session',
        'description' => 'Le modèle d\'horaire pour les offres du produit d\'apprentissage.',
        'help'        => 'Par exemple : « Ce cours s\'offre en cinq séances de 2 heures, à raison d\'une séance par semaine. »'
    ],
    'external_delivery' => [
        'label'       => 'Offre externe',
        'description' => 'L\'indicateur indiquant si ce produit d\'apprentissage peut être donné à l’extérieur de l\'EFPC ou non.',
    ],
    'rooms' => [
        'quantity' => [
            'label'       => 'Quantité',
            'description' => 'Le nombre de salles de ce type requises par offre.',
        ],
        'room_usage' => [
            'label'       => 'Usage',
            'description' => 'L\'usage de cette salle dans ce produit d\'apprentissage.',
        ],
        'room_type' => [
            'label'       => 'Type',
            'description' => 'Le type de salle requis.',
        ],
        'room_layout' => [
            'label'       => 'Aménagement',
            'instruction' => 'Veuillez sélectionner un aménagement de salle à partir de la liste ci-dessous, ou fournir les descriptions en anglais et en français de toute autre aménagement de salle requis.',
            'description' => 'L\'aménagement de la salle.',
            'help'        => '
                <ul>
                    <li><span>Groupes de tables</span> : Aussi appelé « des îlots ».</li>
                    <li><span>Rangées - chaises uniquement</span> : Aussi apellé « de style amphithéâtre ».</li>
                </ul>
            ',
        ],
        'room_layout_other' => [
            'label'       => 'Autre aménagement',
            'description' => 'La description d\'une autre configuration particulière des tables de la salle principale.',
        ],
        'special_requirement_description' => [
            'label'       => 'Exigences particulières',
            'instruction' => 'Veuillez fournir les descriptions en anglais et en français de toute exigence particulière pour la salle.',
            'description' => 'Les descriptions en anglais et en français de toute exigence particulière pour la salle.',
        ],
    ],
    'materials' => [
        'quantity' => [
            'label'       => 'Quantité',
            'description' => 'Le nombre d’éléments requis, par dénominateur indiqué.',
        ],
        'material_item' => [
            'label'       => 'Élément',
            'instruction' => 'Veuillez sélectionner un élément à partir de la liste ci-dessous, ou fournir les descriptions en anglais et en français de tout autre élément requis.',
            'description' => 'La description de l\'élément requis.',
        ],
        'material_item_other' => [
            'label'       => 'Autre élément',
            'description' => 'La description d\'un autre élément requis.',
        ],
        'material_denominator' => [
            'label'       => 'Par',
            'description' => 'Le dénominateur de quantité.',
        ],
        'material_location' => [
            'label'       => 'Où',
            'description' => 'L’endroit où l’élément doit se trouver, doit être installé ou doit être disponible.',
        ],
        'reusable' => [
            'label'       => 'Réutilisable',
            'description' => 'L\'indicateur indiquant si le matériel est réutilisable pour plus d\'une offre.',
        ],
        'notes' => [
            'label'       => 'Remarques',
            'instruction' => 'Au besoin, veuillez fournir les descriptions en anglais et en français de tout autre renseignement pertinent relatif à cet item.',
            'description' => 'Les descriptions en anglais et en français de tout autre renseignement pertinent relatif à cet item.',
            'help'        => 'Par exemple : « Les marqueurs doivent être rouges et bleus », « Le logiciel XYZ version 2.3 doit être pré-installé sur les ordinateurs des étudiants ».',
        ],
    ],
    'documents' => [
        'quantity' => [
            'label'       => 'Quantité',
            'description' => 'Le nombre de copies papier requis, selon le dénominateur indiqué.',
        ],
        'document_category' => [
            'label'       => 'Catégorie',
            'instruction' => 'Veuillez sélectionner une catégorie de document à partir de la liste ci-dessous, ou fournir les descriptions en anglais et en français de toute autre catégorie de document requise.',
            'description' => 'La catégorie générale du document requis.',
        ],
        'document_category_other' => [
            'label'       => 'Autre Catégorie',
            'description' => 'La description d\'une autre catégorie générale du document requis.',
        ],
        'document_denominator' => [
            'label'       => 'Par',
            'description' => 'Le dénominateur de quantité.',
        ],
        'title' => [
            'label'       => 'Titre',
            'instruction' => 'Veuillez fournir les titres en anglais et en français de ce document.',
            'description' => 'Les titres officiels en anglais et en français du document.',
        ],
        'version' => [
            'label'       => 'Version',
            'description' => 'La version requise du document.',
        ],
        'link' => [
            'label'       => 'Lien',
            'description' => 'Les liens vers les versions anglaise et française du document.',
            'instruction' => 'Veuillez fournir les liens (addresses URL) vers les versions anglaise et française de ce document. Utilisez les expressions « Hard Copy » et « Copie papier » respectivement s\'il n\'existe aucune copie électronique.',
            'help'        => 'Dans la plupart des cas, utilisez simplement l\'adresse URL du document dans GCdocs.',
        ],
        'printing_specifications' => [
            'label'       => 'Spécifications d\'impression',
            'instruction' => 'Au besoin, veuillez fournir les descriptions en anglais et en français de toute spécification d\'impression spéciale pour ce document.',
            'description' => 'Les descriptions en anglais et en français de toute spécification d\'impression spéciale du document.',
            'help'        => 'Pensez aux éléments suivants : couleur, taille, finition du papier, recto ou recto verso, agrafes, perforation, etc.',
        ],
        'reusable' => [
            'label'       => 'Réutilisable',
            'description' => 'Un indicateur indiquant si le document est réutilisable pour plus d\'une offre.',
        ],
        'notes' => [
            'label'       => 'Remarques',
            'instruction' => 'Au besoin, veuillez fournir les descriptions en anglais et en français de tout autre renseignement pertinent relatif à ce document.',
            'description' => 'Les descriptions en anglais et en français de tout autre renseignement pertinent relatif à ce document.',
        ],
    ],
    'should_be_published' => [
        'label'       => 'Devrait être publié sur GCcampus',
        'description' => 'Un indicateur pour préciser si le produit d’apprentissage devrait être publié sur GCcampus.',
    ],
    'note_content' => [
        'label'       => 'Contenu de la note dans GCcampus',
        'description' => 'Le contenu brut (en anglais ou en français) de la note du produit d’apprentissage à publier sur GCcampus, le cas échéant.',
    ],
    'disclaimer_content' => [
        'label'       => 'Contenu de l’avertissement dans GCcampus',
        'description' => 'Le contenu brut (en anglais ou en français) de l’avertissement concernant le produit d’apprentissage à afficher sur GCcampus, le cas échéant.',
    ],
    'summary_content' => [
        'label'       => 'Contenu du sommaire pour GCcampus',
        'description' => 'Le contenu brut (en anglais ou en français) du sommaire du produit d’apprentissage à afficher sur GCcampus, le cas échéant.',
    ],
    'comments' => [
        'label'       => 'Commentaires généraux',
        'description' => 'Tout autre renseignement pertinent relatif aux détails opérationnels.',
    ],
];
