<?php

namespace App\Models\Process\Blueprints;

use App\Process\ProcessDefinitionBlueprint;

class CourseDevelopment extends ProcessDefinitionBlueprint
{
    public function getDefinition()
    {
        return [
            'entity_types' => ['course'],
            'name_en'      => 'Course Development',
            'name_fr'      => 'Développement d’un cours',
        ];
    }

    public function getStructure()
    {
        return collect([
            [
                'name_en' => 'Design',
                'name_fr' => 'Conception',
                'forms'   => [
                    [
                        'name_en' => 'Design',
                        'name_fr' => 'Conception',
                    ],
                    [
                        'name_en'     => 'Design Assessment',
                        'name_fr'     => 'Évaluation de la conception',
                        'assessments' => ['design'],
                    ],
                ],
            ],
            [
                'name_en' => 'Development',
                'name_fr' => 'Développement',
                'forms'   => [
                    [
                        'name_en' => 'Operational Details',
                        'name_fr' => 'Détails opérationnels',
                    ],
                    [
                        'name_en' => 'Solution Development',
                        'name_fr' => 'Développement de la solution',
                    ],
                    [
                        'name_en' => 'Offering and Enrolment Estimates',
                        'name_fr' => 'Estimations de l’offre et des inscriptions',
                    ],
                    [
                        'name_en'     => 'Development Assessment',
                        'name_fr'     => 'Évaluation du développement',
                        'assessments' => ['operational-details', 'solution-development', 'offering-and-enrolment-estimates'],
                    ],
                ],
            ],
            [
                'name_en' => 'Communications Review',
                'name_fr' => 'Revue des communications',
                'forms'   => [
                    [
                        'name_en' => 'Communications Material',
                        'name_fr' => 'Matériel de communication',
                    ],
                    [
                        'name_en'     => 'Communications Material Assessment',
                        'name_fr'     => 'Évaluation du matériel de communication',
                        'assessments' => ['communications-material'],
                    ],
                ],
            ],
        ]);
    }

    public function getNotifications()
    {
        return collect([
            [
                'name_en' => 'Design Ready for Assessment',
                'name_fr' => 'Conception prête pour l’évaluation',
                'body_en' => 'A form was submitted and is ready for Design Assessment.\n\nFollowing the assessment, you will need to login to the Learning Product Application (LPA), and submit your decision.',
                'body_fr' => 'Un formulaire a été soumis et est prêt pour l’évaluation de la conception.\n\nAprès l’évaluation, vous devrez vous connecter à l’application des produits d’apprentissage (APA) et soumettre votre décision.',
            ],
            [
                'name_key' => 'entity-cancelled',
                'name_en'  => 'Cancelled',
                'name_fr'  => 'Annulé',
                'body_en'  => 'Your course development has been cancelled. Please take a moment to review the rationale behind this decision by consulting the information sheet\n\nThank you for your time and collaboration.\nThe Learning Product Application Team',
                'body_fr'  => 'Votre développement de cours a été annulé. Veuillez prendre quelques instants pour examiner les raisons qui ont motivé cette décision en consultant la fiche d’information.\n\nMerci pour votre temps et votre collaboration.\n\nL’équipe de l’application des produits d’apprentissage',
            ],
            [
                'name_key' => 'design-rejected',
                'name_en'  => 'Design Requires Adjustments',
                'name_fr'  => 'Conception nécessite des ajustements',
                'body_en'  => 'Following the review of your course development, modifications were recommended to your learning product design. Click the link below, consult the information sheet "Design Assessment" for details and adjust your design accordingly. Once completed, please re-submit the form.\n\nThank you for your time and collaboration.\n\nThe Learning Product Application Team',
                'body_fr'  => 'Suite à l’examen de votre développement de cours, des ajustements ont été recommandés pour la conception de votre produit d’apprentissage. Cliquez sur le lien ci-dessous, référez-vous à la fiche d’information «Évaluation de la conception» pour plus de détail et ajustez la conception de votre produit. Une fois terminé, veuillez soumettre à nouveau le formulaire.\n\nMerci pour votre temps et votre collaboration.\n\nL’équipe de l’application des produits d’apprentissage',
            ],
            [
                'name_en' => 'Operational Details Required',
                'name_fr' => 'Détails opérationnels requis',
                'body_en' => 'The design plan for this learning product was approved. You can now proceed to complete the Operational Details form in the Learning Product Application (LPA) by clicking the link below. Once completed, please submit the form in the LPA.\n\nThank you for your time and collaboration.\n\nThe Learning Product Application Team',
                'body_fr' => 'Le plan de conception de ce produit d’apprentissage a été approuvé. Vous pouvez maintenant compléter le formulaire «Détails opérationnels» dans l’Application des produits d’apprentissage (APA) en cliquant sur le lien ci-dessous. Une fois terminé, veuillez soumettre le formulaire dans l’APA.\n\nMerci pour votre temps et votre collaboration.\n\nL’équipe de l’application des produits d’apprentissage',
            ],
            [
                'name_en' => 'Solution Development Required',
                'name_fr' => 'Développement de la solution requis',
                'body_en' => 'The design plan for this learning product was approved. You can now proceed to complete the Solution Development form in the Learning Product Application (LPA) by clicking the link below. Once completed, please submit the form in the LPA.\n\nThank you for your time and collaboration.\n\nThe Learning Product Application Team',
                'body_fr' => 'Le plan de conception de ce produit d’apprentissage a été approuvé. Vous pouvez maintenant compléter le formulaire «Développement de la solution» dans l’Application des produits d’apprentissage (APA) en cliquant sur le lien ci-dessous. Une fois terminé, veuillez soumettre le formulaire dans l’APA.\n\nMerci pour votre temps et votre collaboration.\n\nL’équipe de l’application des produits d’apprentissage',
            ],
            [
                'name_en' => 'Offering and Enrolment Estimates Required',
                'name_fr' => 'Estimations de l’offre et des inscriptions requis',
                'body_en' => 'The design plan for this learning product was approved. You can now proceed to complete the Offerings and Enrolment Estimates form in the Learning Product Application (LPA by clicking the link below. Once completed, please submit the form in the LPA.\n\nThank you for your time and collaboration.\n\nThe Learning Product Application Team',
                'body_fr' => 'Le plan de conception de ce produit d’apprentissage a été approuvé. Vous pouvez maintenant compléter le formulaire «Estimations de l’offre et des inscriptions» dans l’Application des produits d’apprentissage (APA) en cliquant sur le lien ci-dessous. Une fois terminé, veuillez soumettre le formulaire dans l’APA.\n\nMerci pour votre temps et votre collaboration.\n\nL’équipe de l’application des produits d’apprentissage',
            ],
            [
                'name_en' => 'Development Ready for Assessment',
                'name_fr' => 'Développement prêt pour l’évaluation',
                'body_en' => 'A form was submitted and is ready for Design Assessment (Gate 2). Please contact the Organization Unit (OU) in order to confirm a presentation date within the next two weeks.\n\nFollowing the presentation, you will be required to login to the Learning Product Application (LPA), and submit your decision.',
                'body_fr' => 'Un formulaire a été soumis et est prêt pour l’évaluation de la conception (Jalon 2). Veuillez contacter l’unité organisationnelle afin de confirmer une date de présentation dans les deux prochaines semaines.\n\nAprès la présentation, vous devrez vous connecter à l’application des produits d’apprentissage (APA) et soumettre votre décision.',
            ],
            [
                'name_key' => 'operational-details-rejected',
                'name_en'  => 'Operational Details Requires Adjustments',
                'name_fr'  => 'Détails opérationnels nécessitent des ajustements',
                'body_en'  => 'Following the review of your course development, modifications were recommended to your operational details. Click the link below, consult the information sheet "Development Assessment" and adjust your course operational details accordingly. Once completed, please re-submit the form.\n\nThank you for your time and collaboration.\n\nThe Learning Product Application Team',
                'body_fr'  => 'Suite à l’examen de votre développement de cours, des ajustements ont été recommandés pour vos détails opérationnels. Cliquez sur le lien en ci-dessous, référez-vous à la fiche d’information «Évaluation du développement» pour plus d’information et ajustez vos détails opérationnels. Une fois terminé, veuillez soumettre à nouveau le formulaire.\n\nMerci pour votre temps et votre collaboration.\n\nL’équipe de l’application des produits d’apprentissage',
            ],
            [
                'name_key' => 'solution-development-rejected',
                'name_en'  => 'Solution Requires Adjustments',
                'name_fr'  => 'Développement de la solution nécessitent des ajustements',
                'body_en'  => 'Following the review of your course development, modifications were recommended to your solution development. Click the link below, consult the information sheet "Development Assessment" for details and adjust your solution development accordingly. Once completed, please re-submit the form.\n\nThank you for your time and collaboration.\n\nThe Learning Product Application Team',
                'body_fr'  => 'Suite à l’examen de votre développement de cours, des ajustements ont été recommandés pour votre développement de la solution. Cliquez sur le lien ci-dessous, référez-vous à la fiche d’information «Approbation du développement» pour plus de détails et ajustez votre développement de la solution. Une fois terminé, veuillez soumettre à nouveau le formulaire.\n\nMerci pour votre temps et votre collaboration.\n\nL’équipe de l’application des produits d’apprentissage',
            ],
            [
                'name_key' => 'offering-and-enrolment-estimates-rejected',
                'name_en'  => 'Offering and Enrolment Estimates Requires Adjustments',
                'name_fr'  => 'Estimations de l’offre et des inscriptions nécessitent des ajustements',
                'body_en'  => 'Following the review of your course development, modifications were recommended to your offering and enrolment estimates. Click the link below, consult the information sheet "Development Assessment" for details and adjust your offering and enrolment estimates accordingly. Once completed, please re-submit the form.\n\nThank you for your time and collaboration.\n\nThe Learning Product Application Team',
                'body_fr'  => 'Suite à l’examen de votre développement de cours, des ajustements ont été recommandés pour vos estimations de l’offre et des inscriptions. Cliquez sur le lien ci-dessous, référez-vous à la fiche d’information «Évaluation du développement» pour plus de détail et ajustez vos estimations de l’offre et des inscriptions. Une fois terminé, veuillez soumettre à nouveau le formulaire.\n\nMerci pour votre temps et votre collaboration.\n\nL’équipe de l’application des produits d’apprentissage',
            ],
            [
                'name_key' => 'communications-material-required',
                'name_en'  => 'Communication Material Required',
                'name_fr'  => 'Matériel de communication requis',
                'body_en'  => 'Following the approval of your course development, you may now proceed with providing your recommendations in both official languages for the official title, description and key words. Click the link below to access the "Communications Material" form in the Learning Product Application (LPA). Once completed, be sure to submit your form in the LPA for review and approval.\n\nThank you for your time and collaboration.\n\nThe Learning Product Application Team',
                'body_fr'  => 'Suite à l’approbation de votre développement de cours, vous pouvez maintenant fournir vos recommandations dans les deux langues officielles pour le titre officiel, la description et les mots-clés. Cliquez sur le lien ci-dessous pour accéder au formulaire «Matériel de communication» dans l’Application des produits d’apprentissage (APA). Une fois rempli, assurez-vous de soumettre votre formulaire dans l’APA pour examen et approbation\n\nMerci pour votre temps et votre collaboration.\n\nL’équipe de l’application des produits d’apprentissage',
            ],
            [
                'name_key' => 'communications-material-ready-for-assessment',
                'name_en'  => 'Communications Material Ready for Assessment',
                'name_fr'  => 'Matériel de communication prêt pour l’évaluation',
                'body_en'  => 'A form was submitted and is ready for assessment. Click the link below and refer to the "Communications Material" information sheet. Once completed, please submit your decision directly in the Learning Product Application (LPA) in the "Communications Material Assessment" form.\n\nThank you for your time and collaboration.\n\nThe Learning Product Application Team',
                'body_fr'  => 'Un formulaire a été soumis et est prêt pour l’évaluation. Cliquez sur le lien ci-dessous et référez-vous à la fiche d’information «Matériel de communication». Une fois complétée, veuillez soumettre votre décision directement dans l’Application des produits d’apprentissage (APA) avec le formulaire «Évaluation du matériel de communication».\n\nMerci pour votre temps et votre collaboration.\n\nL’équipe de l’application des produits d’apprentissage',
            ],
            [
                'name_key' => 'communications-material-rejected',
                'name_en'  => 'Communication Material Requires Adjustments',
                'name_fr'  => 'Matériel de communication nécessite des ajustements',
                'body_en'  => 'Following the review of your Communication Material form, modifications were recommended to your communication material. Click the link below, consult the information sheet "Communications Materials Assessment" for details and adjust your communications material accordingly. Once completed, please re-submit the form.\n\nThank you for your time and collaboration.\n\nThe Learning Product Application Team',
                'body_fr'  => 'Suite à l’examen de votre formulaire «Matériel de communication», des ajustements ont été recommandés pour votre matériel de communication. Cliquez sur le lien ci-dessous, référez-vous à la fiche d’information «Évaluation du matériel de communication» pour plus de détail et ajustez votre matériel de communication. Une fois terminé, veuillez soumettre à nouveau le formulaire.\n\nMerci pour votre temps et votre collaboration.\n\nL’équipe de l’application des produits d’apprentissage',
            ],
            [
                'name_en' => 'Ready for Publishing',
                'name_fr' => 'Prêt pour la publication',
                'body_en' => 'This course’s communications material has been approved. Please take note of the upcoming steps:\n1) contact ILMS to create the course in their system.\n2) contact GCcampus to generate an unpublished web page for the course.\n3) confirm the launch and publishing of the course.',
                'body_fr' => 'Le matériel de communication de ce cours a été approuvé. Veuillez prendre note des prochaines étapes:\n1) contacter ILMS pour créer le cours dans son système.\n2) contacter GCcampus pour générer une page Web non publiée pour le cours.\n3) confirmer le lancement et la publication du cours.',
            ],
        ]);
    }

    public function getInformationSheets()
    {
        return collect([
            [
                'name_key'         => 'design-assessment',
                'name_en'          => 'Design Assessment',
                'name_fr'          => 'Évaluation de la conception',
                'display_sequence' => 1,
            ],
            [
                'name_key'         => 'development-assessment',
                'name_en'          => 'Development Assessment',
                'name_fr'          => 'Évaluation du développement',
                'display_sequence' => 2,
            ],
            [
                'name_key'         => 'communications-material-assessment',
                'name_en'          => 'Communications Material Assessment',
                'name_fr'          => 'Évaluation du matériel de communication',
                'display_sequence' => 3,
            ],
        ]);
    }

    public function authorize($learningProduct)
    {
        // Only start this process if learning product is still new.
        return $learningProduct->state->name_key == 'new';
    }

    public function getProcessVariablesFor($learningProduct)
    {
        return [
            // Send course type so that the process can ajdust itself.
            'courseType' => ['type' => 'String', 'value' => $learningProduct->subType->name_key],
        ];
    }
}
