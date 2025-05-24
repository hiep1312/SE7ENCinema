<?php
    namespace SE7ENCinema;

    use Livewire\Attributes\{Locked, On};

    trait scAlert {
        #[Locked]
        public $sc_baseConfigAlert = [];
        #[Locked]
        public $sc_Status = null;

        protected function sc_addBaseAlert(array $options){
            $this->sc_baseConfigAlert = array_merge($this->sc_baseConfigAlert, $options);
            $this->dispatch('_scUpdateBase', $this->sc_baseConfigAlert);
        }

        protected function sc_setBaseAlert(array $options){
            $this->sc_baseConfigAlert = $options;
            $this->dispatch('_scUpdateBase', $this->sc_baseConfigAlert);
        }

        protected function sc_resetBaseAlert(){
            $this->sc_baseConfigAlert = [];
        }

        public function scAlert(string|array $title, string $html = "", ?string $icon = null){
            $options = is_array($title) ? $title : compact('title', 'html', 'icon');
            $this->dispatch('_scAlert', array_merge($this->sc_baseConfigAlert, $options));
        }

        public function scConfirm(string|array $title, string $html = "", ?string $icon = null){
            $options = is_array($title) ? $title : compact('title', 'html', 'icon');
            $this->dispatch('_scConfirm', array_merge($this->sc_baseConfigAlert, $options));
        }

        public function scPrompt(string|array $title, string $html = "", ?string $icon = null, string $input = "text"){
            $options = is_array($title) ? $title : compact('title', 'html', 'icon', 'input');
            $this->dispatch('_scPrompt', array_merge($this->sc_baseConfigAlert, $options));
        }

        public function scToast(string|array $title, ?string $icon = null, int $timer = 3000, bool $progressBar = false){
            $options = is_array($title) ? $title : compact('title', 'icon', 'timer', 'progressBar');
            $this->dispatch('_scToast', array_merge($this->sc_baseConfigAlert, $options));
        }

        public function scProgress(string|array $title, string $html = "", ?string $icon = null, int $timer = 3000){
            $options = is_array($title) ? $title : compact('title', 'html', 'icon', 'timer');
            $this->dispatch('_scProgress', array_merge($this->sc_baseConfigAlert, $options));
        }

        #[On('_scResult')]
        public function scResult(){
            $this->sc_Status = true;
        }
    }
