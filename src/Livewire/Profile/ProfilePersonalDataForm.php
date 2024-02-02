<?php

namespace Pardalsalcap\Hailo\Livewire\Profile;

use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Pardalsalcap\Hailo\Actions\Profile\UpdatePersonalData;
use Pardalsalcap\Hailo\Forms\Traits\HasForms;
use Pardalsalcap\Hailo\Repositories\UserRepository;
use Pardalsalcap\Hailo\Tables\Traits\HasActions;
use Throwable;

class ProfilePersonalDataForm extends Component
{
    use HasActions, HasForms;

    public string $user_form_title = "";

    public function mount(): void
    {
        $this->action = 'edit';
        $this->user_form_title = __('hailo::profile.user_form_title');
    }
    public function cancel(): void
    {
        $this->action = 'edit';
        $this->load = true;
        $this->user_form_title = __('hailo::profile.user_form_title');
    }

    public function update(): void
    {
        try {
            DB::beginTransaction();
            $this->load = false;
            $user = $this->loadModel();
            $this->form(UserRepository::profile($user));
            $this->validate($this->validationRules($this->getForm('profile_form')));
            UpdatePersonalData::run($this->getFormData('profile_form'), $user);
            $this->cancel();
            $this->dispatch('toast-success', ['title' => __('hailo::profile.saved')]);
            $this->dispatch('profileUpdated');
            $this->load = true;
            DB::commit();
        } catch (Throwable $e) {
            DB::rollBack();

            $this->addValidationErrors('profile_form', $e->errors());
            $this->clearValidation();
        }
    }

    public function loadModel(): Model
    {
        return auth()->user();
    }

    public function render(): View|Factory
    {
        $this->form(UserRepository::profile($this->loadModel()))
            ->action('update')
            ->title($this->user_form_title);
        $this->processFormElements($this->getForm('profile_form'), $this->getForm('profile_form')->getSchema());

        if ($this->load)
        {
            auth()->user()->load("preferences");
            foreach(auth()->user()->preferences->pluck('value', 'key') as $key=>$value) {
                $this->addFormData('profile_form', $key, $value);
            }
        }
        else
        {
            //dd($this->formData);
        }

        return view('hailo::livewire.permissions.profile_personal', [
            'profile_form' => $this->getForm('profile_form'),
            'validation_errors' => $this->getValidationErrors()
        ])
            ->layout('hailo::layouts.main')
            ->title(__('hailo::profile.html_title', ['name' => config('app.name')]));
    }
}
