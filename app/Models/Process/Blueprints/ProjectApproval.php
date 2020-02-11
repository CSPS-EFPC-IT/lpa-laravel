<?php

namespace App\Models\Process\Blueprints;

use App\Process\ProcessDefinitionBlueprint;

class ProjectApproval extends ProcessDefinitionBlueprint
{
    public function getDefinition()
    {
        return [
            'entity_types' => ['project'],
            'name_en'      => 'Project Approval',
            'name_fr'      => 'Approbation d\'un projet',
        ];
    }

    public function getStructure()
    {
        return collect([
            [
                'name_key' => 'gate-one-approval',
                'name_en'  => 'Gate 1 Approval',
                'name_fr'  => 'Approbation Jalon 1',
                'forms'    => [
                    [
                        'name_en' => 'Business Case',
                        'name_fr' => 'Plan d\'affaire',
                    ],
                    [
                        'name_en' => 'Planned Product List',
                        'name_fr' => 'Liste de produits prévus',
                    ],
                    [
                        'name_key'    => 'gate-one-approval',
                        'name_en'     => 'Gate 1 Approval',
                        'name_fr'     => 'Approbation Jalon 1',
                        'assessments' => ['business-case', 'planned-product-list'],
                    ],
                ],
            ],
        ]);
    }

    public function getNotifications()
    {
        return collect([
            [
                'name_key' => 'project-ready-for-gate-one-approval',
                'name_en'  => 'Project Ready for Gate 1 Approval',
                'name_fr'  => 'Projet prêt pour Jalon 1',
                'body_en'  => 'A project was submitted and is ready for Gate 1 assessment.\n\nFollowing the assessment, you will need to log into the Learning Product Application (LPA) and submit your decision.',
                'body_fr'  => 'Un projet a été soumis et est prêt à être évalué au Jalon 1.\n\nAprès l’évaluation, vous devrez vous connecter à l’application des produits d’apprentissage (APA) et soumettre votre décision.',
            ],
            [
                'name_key' => 'business-case-rejected',
                'name_en'  => 'Business Case Requires Adjustments',
                'name_fr'  => 'Plan d’affaire nécessite des ajustements',
                'body_en'  => 'Following the review of your project, adjustments were recommended to your business case. Click the link below, consult the information sheet "Gate 1 - Project Approval" for details and adjust your business case accordingly. Once completed, please re-submit the form.\n\nThank you for your time and collaboration.\n\nThe Learning Product Application Team',
                'body_fr'  => 'Suite à l’examen de votre projet, des ajustements ont été recommandés pour votre plan d’affaire. Cliquez sur le lien ci-dessous, référez-vous à la fiche dinformation «Jalon 1 - Approbation du projet» pour plus de détail et ajustez votre plan d’affaire. Une fois terminé, veuillez soumettre à nouveau le formulaire.\n\nMerci pour votre temps et votre collaboration.\n\nL’équipe de l’application des produits d’apprentissage',
            ],
            [
                'name_key' => 'planned-product-list-rejected',
                'name_en'  => 'Planned Product List Requires Adjustments',
                'name_fr'  => 'Liste de produits planifiés nécessite des ajustements',
                'body_en'  => 'Following the review of your project, adjustments were recommended to your Planned Product List. Click the link below, consult the information sheet "Gate 1 - Project Approval" for details and adjust your Planned Product List. Once completed, please re-submit the form.\n\nThank you for your time and collaboration.\n\nThe Learning Product Application Team',
                'body_fr'  => 'Suite à l’examen de votre projet, des modifications ont été recommandées pour votre liste de produits prévus. Cliquez sur le lien ci-dessous, référez-vous à la fiche d’information «Jalon 1 - Approbation du projet» pour plus de détails et ajustez votre liste de produits prévus. Une fois terminé, veuillez soumettre à nouveau le formulaire.\n\nMerci pour votre temps et votre collaboration.\n\nL’équipe de l’application des produits d’apprentissage',
            ],
            [
                'name_key' => 'project-cancelled',
                'name_en'  => 'Project Cancelled',
                'name_fr'  => 'Projet annulé',
                'body_en'  => 'Your project has been cancelled. Please take a moment to review the rationale behind this decision by consulting the information sheet.\n\nThank you for your time and collaboration.\n\nThe Learning Product Application Team',
                'body_fr'  => 'Votre projet a été annulé. Veuillez prendre quelques instants pour examiner les raisons qui ont motivé cette décision en consultant la fiche d’information.\n\nMerci pour votre temps et votre collaboration.\n\nL’équipe de l’application des produits d’apprentissage',
            ],
            [
                'name_key' => 'project-approved',
                'name_en'  => 'Project Approved',
                'name_fr'  => 'Projet approuvé',
                'body_en'  => 'Following the review of the business case and the planned product list, your project has been approved. You may now proceed to create the learning products linked to this project by logging into the Learning Product Application (LPA).\n\nThank you for your time and collaboration.\n\nThe Learning Product Application Team',
                'body_fr'  => 'Suite à l’examen du plan d’affaire et de la liste des produits planifiés, votre projet a été approuvé. Vous pouvez maintenant créer des produits d’apprentissage liés à ce projet en vous connectant à l’application des produits d’apprentissage (APA).\n\nMerci pour votre temps et votre collaboration.\n\nL’équipe de l’application des produits d’apprentissage',
            ],
        ]);
    }

    public function getInformationSheets()
    {
        return collect([
            [
                'name_key'         => 'gate-one-project-approval',
                'name_en'          => 'Gate 1 – Project Approval',
                'name_fr'          => 'Jalon 1 – Approbation de projet',
                'display_sequence' => 1,
            ],
        ]);
    }

    public function authorize($project)
    {
        // Only start this process if learning product is still new.
        return $project->state->name_key == 'new';
    }
}
