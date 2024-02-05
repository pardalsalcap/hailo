<?php

namespace Pardalsalcap\Hailo\Livewire\Profile;

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

    protected UserRepository $repository;

    public string $user_form_title = '';

    public function mount(): void
    {
        $this->action = 'edit';
        $this->user_form_title = __('hailo::profile.user_form_title');
        $this->repository = new UserRepository();
        $this->loadForms();
    }

    public function loadForms()
    {
        $this->form($this->repository->profile($this->loadModel()))
            ->title($this->user_form_title);
        $this->loadPreferences();
    }

    public function hydrate()
    {
        $this->repository = new UserRepository();
        $this->loadForms();
    }

    public function cancel(): void
    {
        $this->action = 'edit';
        $this->load = true;
        $this->loadForms();
    }

    public function update(): void
    {
        try {
            DB::beginTransaction();
            $this->load = false;
            $user = $this->loadModel();
            $form = $this->form($this->repository->profile($user));
            $this->validate($this->validationRules($form));
            UpdatePersonalData::run($this->getFormData($form->getName()), $user);
            $this->success();
            DB::commit();
        } catch (Throwable $e) {
            DB::rollBack();
            $this->handleFormException($e, $form->getName(), __('hailo::users.not_saved'));
        }
    }

    public function loadModel(): Model
    {
        return auth()->user();
    }

    public function loadPreferences(): void
    {
        if ($this->load) {
            auth()->user()->load('preferences');
            foreach (auth()->user()->preferences->pluck('value', 'key') as $key => $value) {
                $this->addFormData('profile_form', $key, $value);
            }
        }
    }

    public function success(): void
    {
        $this->cancel();
        $this->dispatch('toast-success', ['title' => __('hailo::profile.saved')]);
        $this->dispatch('profileUpdated');
    }

    public function render(): View|Factory
    {
        $form = $this->form($this->repository->profile($this->loadModel()))
            ->action('update')
            ->title($this->user_form_title);
        $this->processFormElements($form, $form->getSchema());

        $this->loadPreferences();

        return view('hailo::livewire.permissions.profile_personal',
            [
                'profile_form' => $this->getForm('profile_form'),
                'validation_errors' => $this->getValidationErrors(),
            ])
            ->layout('hailo::layouts.main')
            ->title(__('hailo::profile.html_title', ['name' => config('app.name')]));
    }
}
