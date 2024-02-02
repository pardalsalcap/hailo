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

class ProfileSecurityForm extends Component
{
    use HasActions, HasForms;

    public string $user_form_title = "";

    public function mount(): void
    {
        $this->action = 'edit';
        $this->user_form_title = __('hailo::profile.security_form_title');
    }
    public function cancel(): void
    {
        $this->action = 'edit';
        $this->user_form_title = __('hailo::profile.security_form_title');
    }

    public function update(): void
    {
        try {
            DB::beginTransaction();
            $this->load = false;
            $user = $this->loadModel();
            $this->form(UserRepository::security($user));
            $this->validate($this->validationRules($this->getForm('security_form')));
            //UpdatePersonalData::run($this->getFormData('security_form'), $user);
            $this->cancel();
            $this->dispatch('toast-success', ['title' => __('hailo::profile.saved')]);
            $this->load = true;
            DB::commit();
        } catch (Throwable $e) {
            DB::rollBack();
            $this->addValidationErrors('security_form', $e->errors());
            $this->clearValidation();
        }
    }

    public function loadModel(): Model
    {
        return auth()->user();
    }


    public function render(): View|Factory
    {
        $this->form(UserRepository::security($this->loadModel()))
            ->action('update')
            ->title($this->user_form_title);
        $this->processFormElements($this->getForm('security_form'), $this->getForm('security_form')->getSchema());

        if ($this->load)
        {
            auth()->user()->load("preferences");
            foreach(auth()->user()->preferences->pluck('value', 'key') as $key=>$value) {
                $this->addFormData('security_form', $key, $value);
            }
        }

        return view('hailo::livewire.permissions.profile_security', [
            'security_form' => $this->getForm('security_form'),
            'validation_errors' => $this->getValidationErrors()
        ])
            ->layout('hailo::layouts.main')
            ->title(__('hailo::profile.html_title', ['name' => config('app.name')]));
    }
}
