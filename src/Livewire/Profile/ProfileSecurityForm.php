<?php

namespace Pardalsalcap\Hailo\Livewire\Profile;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Pardalsalcap\Hailo\Actions\Profile\UpdateSecurityData;
use Pardalsalcap\Hailo\Forms\Form;
use Pardalsalcap\Hailo\Forms\Traits\HasForms;
use Pardalsalcap\Hailo\Repositories\UserRepository;
use Pardalsalcap\Hailo\Tables\Traits\HasActions;
use Throwable;

class ProfileSecurityForm extends Component
{
    use HasActions, HasForms;

    protected UserRepository $repository;
    public string $user_form_title = "";

    public function mount(): void
    {
        $this->action = 'edit';
        $this->user_form_title = __('hailo::profile.security_form_title');
        $this->repository = new UserRepository();
        $this->loadForms();
    }

    public function loadForms(): Form
    {
        return $this->form($this->repository->security($this->loadModel()))
            ->title($this->user_form_title);
    }

    public function hydrate(): void
    {
        $this->repository = new UserRepository();
        $this->loadForms();
    }

    public function cancel(): void
    {
        $this->action = 'edit';
        $this->user_form_title = __('hailo::profile.security_form_title');
        $this->loadForms();
    }

    public function update(): void
    {
        try {
            DB::beginTransaction();
            $this->load = false;
            $form = $this->form($this->repository->security($this->loadModel()));
            $this->validate($this->validationRules($form));
            UpdateSecurityData::run($this->getFormData($form->getName()), $this->loadModel());
            $this->success();
            DB::commit();
        } catch (Throwable $e) {
            DB::rollBack();
            $this->handleFormException($e, $form->getName(), __('hailo::users.not_saved'));
        }
    }

    public function success(): void
    {
        $this->cancel();
        $this->dispatch('toast-success', ['title' => __('hailo::profile.saved')]);
        $this->load = true;
    }

    public function loadModel(): Authenticatable
    {
        return auth()->user();
    }


    public function render(): View|Factory
    {
        $this->processFormElements($this->getForm('security_form'), $this->getForm('security_form')->getSchema());
        return view('hailo::livewire.permissions.profile_security', [
            'security_form' => $this->getForm('security_form'),
            'validation_errors' => $this->getValidationErrors()
        ])
            ->layout('hailo::layouts.main')
            ->title(__('hailo::profile.html_title', ['name' => config('app.name')]));
    }
}
