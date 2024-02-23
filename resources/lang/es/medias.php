<?php

return [
    'table_title' => 'Multimedia',
    'field_label_url' => 'Url',
    'field_label_title' => 'Título',
    'field_label_alt' => 'Texto alternativo',
    'field_label_filename' => 'Nombre del fichero',

    'field_placeholder_title' => 'Escribe el título de la imagen',
    'field_placeholder_alt' => 'Escribe el texto alternativo de la imagen',
    'field_placeholder_filename' => 'Nombre del fichero',

    'field_help_title' => 'El título de la imagen no es visible para los visitantes pero es importante para el SEO, además se utiliza para asistir a las personas con discapacidad visual',
    'field_help_alt' => 'El texto alternativo de la imagen no es visible para los visitantes pero es importante para el SEO, además se utiliza para asistir a las personas con discapacidad visual',
    'field_help_filename' => 'Puede cambiar el nombre del fichero si lo desea, considere la posibilidad de crear una redirección si el fichero ha sido publicado previamente. EL nombre del fichero no debe incluir la extensión',

    'media_form_title' => 'Carga de multimedia',
    'media_form_title_edit' => 'Editando el fichero :u',

    'confirm_delete_title' => 'Confirma la eliminación del fichero',
    'confirm_delete_text' => 'El fichero será eliminado y no podrá recuperarse',
    'deleted' => 'Fichero eliminado',
    'not_deleted' => 'El fichero no ha podido ser eliminado',

    'not_found' => 'Fichero no encontrado',

    'html_title' => 'Gestión de los ficheros multimedia',
    'section_title_config' => 'Opciones de configuración',
    'crops_title' => 'Recortes generados',
    'crops_text' => 'Cuando cargas una imagen en el CMS se generan automáticamente diferentes versiones de la misma con diferentes tamaños, estas versiones se utilizan en diferentes partes de la web, por ejemplo en las galerías de imágenes o en los listados de noticias. Estas imágenes se recortan a tamaños específicos para optimizar la carga de los elementos multimedia en cada parte de la web. Puedes ver las versiones generadas para cada imagen en esta sección. Y editar el encuadre de la imagen si lo necesitas. Recuerda que si editas el encuadre de una imagen, la versión original no se verá afectada, solo se generará una nueva versión con el nuevo encuadre.',

    'version' => 'Versión ',
    'version_text' => 'Si has cargado una imagen específica para esta versión, se mostrará aquí. Si no, se mostrará la versión generada automáticamente para la web. La herramienta de edición se basa siempre en la imagen original, no en las imágenes cargadas como sustitución',

    'saved' => 'Fichero guardado',
    'not_saved' => 'El fichero no ha podido ser guardado',

    'no_records_found' => 'No se han encontrado fciheros',

    'file_is_too_big' => ' es demasiado grande, el tamaño máximo permitido es :s',

    'missing_title' => 'Falta el título',
    'missing_alt' => 'Falta el texto alternativo',

    'file_exists' => 'Ya existe un fichero con ese nombre',
    'url_exists' => 'El nuevo nombre del fichero ya esta siendo utilizado por otro fichero',
    'not_renamed' => 'No se ha podido renombrar el fichero',

    'curation_cms_information' => 'Esta versión de tu imagen se utilizará solo en el CMS, se generará automáticamente cuando cargues una imagen y se redimensionará a 300x300 píxeles en formato webp',
    'curation_max_information' => 'Esta versión de tu imagen se utilizará en la web, se generará automáticamente cuando cargues una imagen y se redimensionará a un máximo de 2000x2000 píxeles en formato webp. Esta imagen se utilizará cuando se muestre la imagen en la web y no se haya generado una versión específica para la sección en la que se muestra o cuando queremos mostrar la imagen en su máxima resolución',
];
