<div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
    <div @class([
   "lg:col-span-3"=>($action=='index'),
    "lg:col-span-2 opacity-20"=>($action=='edit'),
])>
        <x-hailo::table :table="$roles_table"/>
    </div>
    <div @class([
   ""=>($action=='index'),
    "lg:col-span-2"=>($action=='edit'),
])>
        <x-hailo::form  :data="$formData[$role_form->getName()]" :validation="$validation_errors[$role_form->getName()]??null" :form="$role_form"/>
    </div>
</div>
