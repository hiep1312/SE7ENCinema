<?php
    namespace SE7ENCinema;

    use Livewire\Attributes\{Locked, On};

    trait scAlert {
        #[Locked]
        public $sc_baseConfigAlert = [];
        #[Locked]
        public $sc_baseModel = "";
        #[Locked]
        public $sc_Status = null;

        protected function sc_addBaseAlert(array $options, string $props = ""){
            $this->sc_baseConfigAlert = array_merge($this->sc_baseConfigAlert, $options);
            $this->sc_baseModel = $props ?: $this->sc_baseModel;
            $this->dispatch('_scUpdateBase', $this->sc_baseConfigAlert);
        }

        protected function sc_setBaseAlert(array $options, string $props = ""){
            $this->sc_baseConfigAlert = $options;
            $this->sc_baseModel = $props ?: $this->sc_baseModel;
            $this->dispatch('_scUpdateBase', $this->sc_baseConfigAlert);
        }

        protected function sc_resetBaseAlert(){
            $this->sc_baseConfigAlert = [];
        }

        protected function sc_setBaseProps(string $props){
            $this->sc_baseModel = $props;
        }

        public function scAlert(string|array $title, string $html = "", ?string $icon = null, string $props = ""){
            $options = is_array($title) ? $title : compact('title', 'html', 'icon');
            !is_array($title) ?: ($props = $html ?: $this->sc_baseModel);
            $this->dispatch('_scAlert', array_merge($this->sc_baseConfigAlert, $options), $props);
        }

        public function scConfirm(string|array $title, string $html = "", ?string $icon = null, string $props = ""){
            $options = is_array($title) ? $title : compact('title', 'html', 'icon');
            !is_array($title) ?: ($props = $html ?: $this->sc_baseModel);
            $this->dispatch('_scConfirm', array_merge($this->sc_baseConfigAlert, $options), $props);
        }

        public function scPrompt(string|array $title, string $html = "", ?string $icon = null, string $input = "text", string $props = ""){
            $options = is_array($title) ? $title : compact('title', 'html', 'icon', 'input');
            !is_array($title) ?: ($props = $html ?: $this->sc_baseModel);
            $this->dispatch('_scPrompt', array_merge($this->sc_baseConfigAlert, $options), $props);
        }

        public function scToast(string|array $title, ?string $icon = null, int $timer = 3000, bool $progressBar = false, string $props = ""){
            $options = is_array($title) ? $title : compact('title', 'icon', 'timer', 'progressBar');
            !is_array($title) ?: ($props = $icon ?: $this->sc_baseModel);
            $this->dispatch('_scToast', array_merge($this->sc_baseConfigAlert, $options), $props);
        }

        public function scProgress(string|array $title, string $html = "", ?string $icon = null, int $timer = 3000, string $props = ""){
            $options = is_array($title) ? $title : compact('title', 'html', 'icon', 'timer');
            !is_array($title) ?: ($props = $html ?: $this->sc_baseModel);
            $this->dispatch('_scProgress', array_merge($this->sc_baseConfigAlert, $options), $props);
        }

        #[On('_scResult')]
        public function scResult($result){
            $this->sc_Status = $result;
        }
    }
