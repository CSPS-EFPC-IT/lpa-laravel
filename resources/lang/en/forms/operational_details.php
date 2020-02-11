<?php

return [
    'form_title' => 'Operational Details',
    'tabs'       => [
        'instructors'      => 'Instructors',
        'guest_speakers'   => 'Guest Speakers',
        'offering_details' => 'Offering Details',
        'rooms'            => 'Rooms',
        'materials'        => 'Materials',
        'documents'        => 'Documents',
        'gc_campus'        => 'GCcampus',
        'comments'         => 'Comments',
    ],
    'form_section_groups' => [
        'instructor' => [
            'label'       => 'Instructor|Instructors',
            'instruction' => 'Please provide the information for each instructor required using the controls below. Use the + button to add another entry to the list.',
            'description' => 'The description of the instructor profiles required to deliver an offer of this learning product.',
        ],
        'guest_speaker' => [
            'label'       => 'Guest Speaker|Guest Speakers',
            'instruction' => 'Please provide the information for each guest speaker required using the controls below. Use the + button to add another entry to the list.',
            'description' => 'The description of the guest speaker profiles required to deliver an offer of this learning product.',
            'help'        => 'Guest Speakers are also known as subject matter experts.'
        ],
        'room' => [
            'label'       => 'Room|Rooms',
            'instruction' => 'Please provide the information for each room required using the controls below. Use the + button to add another entry to the list.',
            'description' => 'The description of the rooms required to deliver an offer of this learning product.',
        ],
        'material' => [
            'label'       => 'Material Item|Material Items',
            'instruction' => 'Please provide the information for each item required using the controls below. Use the + button to add another entry to the list.',
            'description' => 'The description of the material required to deliver an offer of this learning product.',
        ],
        'document' => [
            'label'       => 'Document|Documents',
            'instruction' => 'Please provide the information for each document required using the controls below. Use the + button to add another entry to the list.',
            'description' => 'The description of the documents required to deliver an offer of this learning product.',
        ],
    ],
    'instructors' => [
        'required_profile' => [
            'label'       => 'Required Profile',
            'instruction' => 'Please provide the English and the French descriptions of the required qualifications, competencies and personal suitability of the course instructor. ',
            'description' => 'The English and the French descriptions of the instructor profile requirements.',
        ],
        'schedule' => [
            'label'       => 'Schedule',
            'instruction' => 'Please provide the English and the French descriptions of the instructor schedule. ',
            'description' => 'The English and the French descriptions of the instructor schedule.',
            'help'        => 'For example: "Day 2 only", "Day 1 and 3", "Day 1 PM only".',
        ],
    ],
    'guest_speakers' => [
        'required_profile' => [
            'label'       => 'Required Profile',
            'instruction' => 'Please provide the English and the French descriptions of the required qualifications, competencies and personal suitability of the guest speaker.',
            'description' => 'The English and the French descriptions of the guest speaker profile requirements.',
        ],
        'schedule' => [
            'label'       => 'Schedule',
            'instruction' => 'Please provide the English and the French descriptions of the guest speaker schedule.',
            'description' => 'The English and the French descriptions of the guest speaker schedule.',
            'help'        => 'For example: "Day 2 only", "Day 1 and 3", "Day 1 PM only".',
        ],
    ],
    'number_of_virtual_producers_per_offering' => [
        'label'       => 'Number of Virtual Producers per Offering',
        'description' => 'The number of virtual producers required per offering.',
        'help'        => 'This control is enabled only when the possible offering types specified in the Design form include "Virtual" or "Simultaneous Virtual and In-Person Classroom".',
    ],
    'minimum_number_of_participant_per_offering' => [
        'label'       => 'Minimum Number of Participants per Offering',
        'description' => 'The minimum number of participants per offering to successfully deliver this learning product.',
    ],
    'maximum_number_of_participant_per_offering' => [
        'label'       => 'Maximum Number of Participants per Offering',
        'description' => 'The maximum number of participants per offering to successfully deliver this learning product.',
    ],
    'optimal_number_of_participant_per_offering' => [
        'label'       => 'Optimal Number of Participants per Offering',
        'description' => 'The optimal number of participants per offering to successfully deliver this learning product.',
    ],
    'waiting_list_maximum_size' => [
        'label'       => 'Maximum Number of Participants per Waiting list',
        'description' => 'The maximum number of participants allowed on the offerings\' waiting list.',
    ],
    'session_template' => [
        'label'       => 'Session template',
        'description' => 'The schedule template for the learning product offerings.',
        'help'        => 'For example: "This course is offered in five sessions of 2 hours, one session per week."'
    ],
    'external_delivery' => [
        'label'       => 'External Delivery',
        'description' => 'The indicator of whether or not this learning product can be delivered outside CSPS.',
    ],
    'rooms' => [
        'quantity' => [
            'label'       => 'Quantity',
            'description' => 'The number of rooms of this kind required per offering.',
        ],
        'room_usage' => [
            'label'       => 'Usage',
            'description' => 'The usage of this room in the learning product.',
        ],
        'room_type' => [
            'label'       => 'Type',
            'description' => 'The type of room needed.',
        ],
        'room_layout' => [
            'label'       => 'Layout',
            'instruction' => 'Please select a room layout from the list below, or provide the English and French descriptions of any other room layout required.',
            'description' => 'The room layout.',
            'help'        => '
                <ul>
                    <li><span>Table Groups</span>: Also known as "Islands".</li>
                    <li><span>Rows - Chairs only</span>: Also known as "Theatre Style".</li>
                </ul>
            ',
        ],
        'room_layout_other' => [
            'label'       => 'Other Layout',
            'description' => 'The description of another specific table layout for the main room.',
        ],
        'special_requirement_description' => [
            'label'       => 'Special Requirements',
            'instruction' => 'Please provide the English and French descriptions of any special requirements for the room.',
            'description' => 'The English and French descriptions of any special requirements for the room.',
        ],
    ],
    'materials' => [
        'quantity' => [
            'label'       => 'Quantity',
            'description' => 'The number of items required per the indicated denominator.',
        ],
        'material_item' => [
            'label'       => 'Item',
            'instruction' => 'Please select an item from the list below, or provide the English and French descriptions of any other item required.',
            'description' => 'The description of the item required.',
        ],
        'material_item_other' => [
            'label'       => 'Other Item',
            'description' => 'The description of another item required.',
        ],
        'material_denominator' => [
            'label'       => 'Per/By',
            'description' => 'The quantity denominator.',
        ],
        'material_location' => [
            'label'       => 'Where',
            'description' => 'The location where the item must be located, installed or available.',
        ],
        'reusable' => [
            'label'       => 'Reusable',
            'description' => 'The indicator of whether the item is reusable for more than one offering.',
        ],
        'notes' => [
            'label'       => 'Notes',
            'instruction' => 'If needed, please provide the English and French descriptions of any other relevant information about this item.',
            'description' => 'The English and French descriptions of any other relevant information about this item.',
            'help'        => 'For example: "Markers must be red and blue", "Software XYZ version 2.3 must be pre-installed on student computer".',
        ],
    ],
    'documents' => [
        'quantity' => [
            'label'       => 'Quantity',
            'description' => 'The number of items required per the indicated denominator.',
        ],
        'document_category' => [
            'label'       => 'Category',
            'instruction' => 'Please select a document category from the list below, or provide the English and French descriptions of any other document category required.',
            'description' => 'The general category of the document required.',
        ],
        'document_category_other' => [
            'label'       => 'Other Category',
            'description' => 'The description of another general category of the document required.',
        ],
        'document_denominator' => [
            'label'       => 'Per/By',
            'description' => 'The quantity denominator.',
        ],
        'title' => [
            'label'       => 'Title',
            'instruction' => 'Please provide the English and French titles for this document.',
            'description' => 'The official English and French titles of the document.',
        ],
        'version' => [
            'label'       => 'Version',
            'description' => 'The required version of the document.',
        ],
        'link' => [
            'label'       => 'Link',
            'description' => 'The links to the English and French versions of the document.',
            'instruction' => 'Please provide the links (URL Addresses) to the English and French versions of this document. Use the expressions "Hard Copy" and "Copie papier" respectively if no electronic copy exists.',
            'help'        => 'In most cases, simply use the document URL address in GCdocs.',
        ],
        'printing_specifications' => [
            'label'       => 'Printing Specifications',
            'instruction' => 'If needed, please provide the English and French descriptions of any special printing specifications for this document.',
            'description' => 'The English and French descriptions of any special printing specifications for the document.',
            'help'        => 'Think about colour, size, paper finish, single/double-sided, staples, hole-punched, etc.',
        ],
        'reusable' => [
            'label'       => 'Reusable',
            'description' => 'An indicator of whether the document is reusable for more than one offering.',
        ],
        'notes' => [
            'label'       => 'Notes',
            'instruction' => 'If needed, please provide the English and French descriptions of any other relevant information about this document.',
            'description' => 'The English and French descriptions of any other relevant information about the document.',
        ],
    ],
    'should_be_published' => [
        'label'       => 'Should be Published on GCcampus',
        'description' => 'An indicator of whether of not the learning product should be published on GCcampus.',
    ],
    'note_content' => [
        'label'       => 'GCcampus Note Content',
        'description' => 'The raw content (in English or French) of the Learning Product Note to display on GCcampus, if applicable.',
    ],
    'disclaimer_content' => [
        'label'       => 'GCcampus Disclaimer Content',
        'description' => 'The raw content (in English or French) of the Learning Product Disclaimer to display on GCcampus, if applicable.',
    ],
    'summary_content' => [
        'label'       => 'GCcampus Summary Content',
        'description' => 'The raw content (in English or French) of the Learning Product Summary to display on GCcampus, if applicable.',
    ],
    'comments' => [
        'label'       => 'General Comments',
        'description' => 'Any other relevant information about the learning product.',
    ],
];
