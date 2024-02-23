<div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
    <div @class([
   "lg:col-span-3"=>($action=='index'),
    "lg:col-span-2 opacity-20"=>($action=='edit'),
])>
        <x-hailo::table :table="$users_table"/>
    </div>
    <div @class([
   ""=>($action=='index'),
    "lg:col-span-2"=>($action=='edit'),
])>
        <x-hailo::form  :data="$formData[$user_form->getName()]" :validation="$validation_errors[$user_form->getName()]??null" :form="$user_form"/>
    </div>
</div>
