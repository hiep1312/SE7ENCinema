import Swal from 'sweetalert2';
const $sc_configAlert = {
    _config: {
        theme: "light",
        showClass: undefined,
        hideClass: undefined,
        footer: "",
        backdrop: true,
        width: undefined,
        padding: undefined,
        color: undefined,
        background: undefined,
        position: "center",
        grow: false,
        customClass: {},
        timer: undefined,
        timerProgressBar: false,
        keydownListenerCapture: true,
        confirmButtonText: "OK",
        denyButtonText: "No",
        cancelButtonText: "Hủy",
        confirmButtonColor: undefined,
        denyButtonColor: undefined,
        cancelButtonColor: undefined,
        returnFocus: false,
        inputLabel: "",
        inputPlaceholder: "",
        inputAutoTrim: true,
        inputAttributes: {},
    },
    _validators: {
        listener_directive: function (directive){
            const {modifiers} = directive;

            /* Get Modified */
            const capture = modifiers.includes('capture'), once = modifiers.includes('once'), passive = modifiers.includes('passive');
            const prevent = modifiers.includes('prevent'), stop = modifiers.includes('stop');

            return {capture, once, passive, prevent, stop};
        },
        icon_directive: function (directive){
            const {modifiers} = directive;
            return modifiers.find(modifier => /warning|error|info|success|question/.test(modifier));
        },
        iconColor_directive: function (directive){
            const {raw} = directive;
            const color = raw.match(/\.icon\.([^\.]+)/);
            return color && color[1];
        },
        toast_directive: function (directive){
            const {modifiers} = directive;
            return modifiers.includes('toast');
        },
        input_directive: function (directive){
            const {raw} = directive;
            const type = raw.match(/\.input\.([^\.]+)/);
            return type && type[1];
        },
        position_directive: function (directive){
            const {raw} = directive;
            const position = raw.match(/\.position\.([^\.]+(?:\.[^\.]+)?)/);
            return position && position[1].replace(/\./g, '-');
        },
        timer_directive: function (directive){
            const {raw} = directive;
            const timer = raw.match(/\.timer(?:\.([^\.]+))?/);
            return timer && (timer[1] || 3000);
        },
        timerProgressBar_directive: function (directive){
            const {modifiers} = directive;
            return modifiers.includes('progress');
        },
        showConfirmButton_directive: function (directive){
            const {modifiers} = directive;
            return modifiers.includes('confirm');
        },
        showDenyButton_directive: function (directive){
            const {modifiers} = directive;
            return modifiers.includes('deny');
        },
        showCancelButton_directive: function (directive){
            const {modifiers} = directive;
            return modifiers.includes('cancel');
        },
        showCloseButton_directive: function (directive){
            const {modifiers} = directive;
            return modifiers.includes('close');
        },
        draggable_directive: function (directive){
            const {modifiers} = directive;
            return modifiers.includes('drag');
        },
        grow_directive: function (directive){
            const {raw} = directive;
            const grow = raw.match(/\.grow\.([^\.]+)/);
            return grow && grow[1];
        },
        inputValue_directive: function (directive){
            const {raw} = directive;
            const inputValue = raw.match(/\.inputvalue\.([^\.]+)/);
            return inputValue && inputValue[1];
        },
        inputValidator_directive: function (directive){
            const {raw} = directive;
            const inputValidator = raw.match(/\.inputvalidator\.([^\.]+)/);
            return inputValidator && inputValidator[1];
        },
        inputOptions_directive: function (directive){
            const {raw} = directive;
            const inputOptions = raw.match(/\.inputoptions\.([^\.]+)/);
            return inputOptions && inputOptions[1];
        },
    },
}

Object.seal($sc_configAlert);

document.addEventListener('livewire:init', () => {
    Livewire.directive('sc-alert', function ({ $wire, el, directive, component, cleanup }){
        const {expression} = directive;

        const title = el.getAttribute('wire:sc-title') ?? expression;
        const html = el.getAttribute('wire:sc-html') ?? '';
        let model = el.getAttribute('wire:sc-model') ?? el.getAttribute('wire:click');
        const useModelLivewire = el.getAttribute('wire:sc-model') ?? true;
        let params = [];
        const checkCallFunction = model.match(/(\w+)(?:\(([^\)]*)\))/u);
        checkCallFunction && (model = checkCallFunction[1], params = JSON.parse(`[${checkCallFunction[2].replace(/\s+,\s+/g, ', ').replace(/'/g, '"')}]`));
        const live = directive.modifiers.includes('live');

        /* Modifier Listener */
        const {prevent, stop, ...optionsListener} = $sc_configAlert._validators.listener_directive(directive);

        /* Modifier Options */
        const icon = $sc_configAlert._validators.icon_directive(directive) ?? undefined;
        const iconColor = $sc_configAlert._validators.iconColor_directive(directive) ?? undefined;
        const position = $sc_configAlert._validators.position_directive(directive) ?? undefined;
        const timer = $sc_configAlert._validators.timer_directive(directive) ?? undefined;
        const showCloseButton = $sc_configAlert._validators.showCloseButton_directive(directive);
        const draggable = $sc_configAlert._validators.draggable_directive(directive);
        const grow = $sc_configAlert._validators.grow_directive(directive) ?? undefined;
        const optionCustom = Object.fromEntries(Object.entries({title, html, icon, iconColor, position, timer, showCloseButton, draggable, grow}).filter(([key, value]) => Boolean(value)));

        const options = Object.assign({}, $sc_configAlert._config, optionCustom);

        const listener = function (e){
            (prevent || useModelLivewire) && e.preventDefault();
            stop && e.stopPropagation();

            Swal.fire(options).then(result => {
                model && (typeof $wire[model]==='function' ? (params.length ? $wire[model](result, ...params) : $wire[model](result)) : $wire[model] = params.length ? [result, ...params] : result);
                live && $wire.$commit();
            });
        };

        el.addEventListener('click', listener, optionsListener);

        cleanup(() => {
            el.removeEventListener('click', listener);
        });
    });

    Livewire.directive('sc-confirm', function ({ $wire, el, directive, component, cleanup }){
        const {expression} = directive;

        const title = el.getAttribute('wire:sc-title') ?? expression;
        const html = el.getAttribute('wire:sc-html') ?? '';
        let model = el.getAttribute('wire:sc-model') ?? el.getAttribute('wire:click');
        const useModelLivewire = el.getAttribute('wire:sc-model') ?? true;
        let params = [];
        const checkCallFunction = model.match(/(\w+)(?:\(([^\)]*)\))/u);
        checkCallFunction && (model = checkCallFunction[1], params = JSON.parse(`[${checkCallFunction[2].replace(/\s+,\s+/g, ', ').replace(/'/g, '"')}]`));
        const live = directive.modifiers.includes('live');

        /* Modifier Listener */
        const {prevent, stop, ...optionsListener} = $sc_configAlert._validators.listener_directive(directive);

        /* Modifier Options */
        const icon = $sc_configAlert._validators.icon_directive(directive) ?? undefined;
        const iconColor = $sc_configAlert._validators.iconColor_directive(directive) ?? undefined;
        const position = $sc_configAlert._validators.position_directive(directive) ?? undefined;
        const timer = $sc_configAlert._validators.timer_directive(directive) ?? undefined;
        const showDenyButton = $sc_configAlert._validators.showDenyButton_directive(directive);
        const showCancelButton = true;
        const showCloseButton = $sc_configAlert._validators.showCloseButton_directive(directive);
        const draggable = $sc_configAlert._validators.draggable_directive(directive);
        const grow = $sc_configAlert._validators.grow_directive(directive) ?? undefined;
        const optionCustom = Object.fromEntries(Object.entries({title, html, icon, iconColor, position, timer, showDenyButton, showCancelButton, showCloseButton, draggable, grow}).filter(([key, value]) => Boolean(value)));

        const options = Object.assign({}, $sc_configAlert._config, optionCustom);

        const listener = function (e){
            (prevent || useModelLivewire) && e.preventDefault();
            stop && e.stopPropagation();
            Swal.fire(options).then(result => {
                model && (typeof $wire[model]==='function' ? (params.length ? $wire[model](result, ...params) : $wire[model](result)) : $wire[model] = params.length ? [result, ...params] : result);
                live && $wire.$commit();
            });
        };

        el.addEventListener('click', listener, optionsListener);

        cleanup(() => {
            el.removeEventListener('click', listener);
        });
    });

    Livewire.directive('sc-prompt', function ({ $wire, el, directive, component, cleanup }){
        const {expression} = directive;

        const title = el.getAttribute('wire:sc-title') ?? expression;
        const html = el.getAttribute('wire:sc-html') ?? '';
        let model = el.getAttribute('wire:sc-model') ?? el.getAttribute('wire:click');
        const useModelLivewire = el.getAttribute('wire:sc-model') ?? true;
        let params = [];
        const checkCallFunction = model.match(/(\w+)(?:\(([^\)]*)\))/u);
        checkCallFunction && (model = checkCallFunction[1], params = JSON.parse(`[${checkCallFunction[2].replace(/\s+,\s+/g, ', ').replace(/'/g, '"')}]`));
        const live = directive.modifiers.includes('live');

        /* Modifier Listener */
        const {prevent, stop, ...optionsListener} = $sc_configAlert._validators.listener_directive(directive);

        /* Modifier Options */
        const icon = $sc_configAlert._validators.icon_directive(directive) ?? undefined;
        const iconColor = $sc_configAlert._validators.iconColor_directive(directive) ?? undefined;
        const position = $sc_configAlert._validators.position_directive(directive) ?? undefined;
        const showDenyButton = $sc_configAlert._validators.showDenyButton_directive(directive);
        const showCancelButton = true;
        const showCloseButton = $sc_configAlert._validators.showCloseButton_directive(directive);
        const draggable = $sc_configAlert._validators.draggable_directive(directive);
        const grow = $sc_configAlert._validators.grow_directive(directive) ?? undefined;
        const input = $sc_configAlert._validators.input_directive(directive) ?? 'text';
        let inputValue = $sc_configAlert._validators.inputValue_directive(directive) ?? '';
        inputValue && (inputValue = $wire.get(inputValue));
        let inputValidator = $sc_configAlert._validators.inputValidator_directive(directive) ?? undefined;
        inputValidator && (inputValidator = $wire[inputValidator]);
        let inputOptions = $sc_configAlert._validators.inputOptions_directive(directive) ?? undefined;
        inputOptions && (input==="select" || input==="radio") && (inputOptions = $wire.get(inputOptions));
        const optionCustom = Object.fromEntries(Object.entries({title, html, icon, iconColor, position, showDenyButton, showCancelButton, showCloseButton, draggable, grow, input, inputValue, inputValidator, inputOptions}).filter(([key, value]) => Boolean(value)));

        const options = Object.assign({}, $sc_configAlert._config, optionCustom);

        const listener = function (e){
            (prevent || useModelLivewire) && e.preventDefault();
            stop && e.stopPropagation();

            Swal.fire(options).then(result => {
                model && (typeof $wire[model]==='function' ? (params.length ? $wire[model](result, ...params) : $wire[model](result)) : $wire[model] = params.length ? [result, ...params] : result);
                live && $wire.$commit();
            });
        };

        el.addEventListener('click', listener, optionsListener);

        cleanup(() => {
            el.removeEventListener('click', listener);
        });

    });

    Livewire.directive('sc-toast', function ({ $wire, el, directive, component, cleanup }){
        const {expression} = directive;

        const title = el.getAttribute('wire:sc-title') ?? expression;
        let model = el.getAttribute('wire:sc-model') ?? el.getAttribute('wire:click');
        const useModelLivewire = el.getAttribute('wire:sc-model') ?? true;
        let params = [];
        const checkCallFunction = model.match(/(\w+)(?:\(([^\)]*)\))/u);
        checkCallFunction && (model = checkCallFunction[1], params = JSON.parse(`[${checkCallFunction[2].replace(/\s+,\s+/g, ', ').replace(/'/g, '"')}]`));
        const live = directive.modifiers.includes('live');

        /* Modifier Listener */
        const {prevent, stop, ...optionsListener} = $sc_configAlert._validators.listener_directive(directive);

        /* Modifier Options */
        const toast = true;
        const icon = $sc_configAlert._validators.icon_directive(directive) ?? undefined;
        const iconColor = $sc_configAlert._validators.iconColor_directive(directive) ?? undefined;
        const position = $sc_configAlert._validators.position_directive(directive) ?? "top-right";
        const timer = $sc_configAlert._validators.timer_directive(directive) ?? 3000;
        const timerProgressBar = $sc_configAlert._validators.timerProgressBar_directive(directive);
        const showConfirmButton = false;
        const showCloseButton = $sc_configAlert._validators.showCloseButton_directive(directive);
        const optionCustom = Object.fromEntries(Object.entries({toast, title, icon, iconColor, position, timer, timerProgressBar, showCloseButton}).filter(([key, value]) => Boolean(value)));
        optionCustom['showConfirmButton'] = showConfirmButton;

        const options = Object.assign({}, $sc_configAlert._config, optionCustom);
        delete options['backdrop']; delete options['keydownListenerCapture']; delete options['returnFocus'];

        const listener = function (e){
            (prevent || useModelLivewire) && e.preventDefault();
            stop && e.stopPropagation();

            Swal.fire(options).then(result => {
                model && (typeof $wire[model]==='function' ? (params.length ? $wire[model](result, ...params) : $wire[model](result)) : $wire[model] = params.length ? [result, ...params] : result);
                live && $wire.$commit();
            });
        };

        el.addEventListener('click', listener, optionsListener);

        cleanup(() => {
            el.removeEventListener('click', listener);
        });
    });

    Livewire.directive('sc-progress', function ({ $wire, el, directive, component, cleanup }){
        const {expression} = directive;

        const title = el.getAttribute('wire:sc-title') ?? expression;
        const html = el.getAttribute('wire:sc-html') ?? '';
        let model = el.getAttribute('wire:sc-model') ?? el.getAttribute('wire:click');
        const useModelLivewire = el.getAttribute('wire:sc-model') ?? true;
        let params = [];
        const checkCallFunction = model.match(/(\w+)(?:\(([^\)]*)\))/u);
        checkCallFunction && (model = checkCallFunction[1], params = JSON.parse(`[${checkCallFunction[2].replace(/\s+,\s+/g, ', ').replace(/'/g, '"')}]`));
        const live = directive.modifiers.includes('live');

        /* Modifier Listener */
        const {prevent, stop, ...optionsListener} = $sc_configAlert._validators.listener_directive(directive);

        /* Modifier Options */
        const icon = $sc_configAlert._validators.icon_directive(directive) ?? undefined;
        const iconColor = $sc_configAlert._validators.iconColor_directive(directive) ?? undefined;
        const position = $sc_configAlert._validators.position_directive(directive) ?? undefined;
        const timer = $sc_configAlert._validators.timer_directive(directive) ?? 3000;
        const timerProgressBar = true;
        const showConfirmButton = $sc_configAlert._validators.showConfirmButton_directive(directive);
        const showDenyButton = $sc_configAlert._validators.showDenyButton_directive(directive);
        const showCancelButton = $sc_configAlert._validators.showCancelButton_directive(directive);
        const showCloseButton = $sc_configAlert._validators.showCloseButton_directive(directive);
        const draggable = $sc_configAlert._validators.draggable_directive(directive);
        const grow = $sc_configAlert._validators.grow_directive(directive) ?? undefined;
        const optionCustom = Object.fromEntries(Object.entries({title, html, icon, iconColor, position, timer, timerProgressBar, showDenyButton, showCancelButton, showCloseButton, draggable, grow}).filter(([key, value]) => Boolean(value)));
        optionCustom['showConfirmButton'] = showConfirmButton;

        const options = Object.assign({}, $sc_configAlert._config, optionCustom);

        const listener = function (e){
            (prevent || useModelLivewire) && e.preventDefault();
            stop && e.stopPropagation();

            Swal.fire(options).then(result => {
                model && (typeof $wire[model]==='function' ? (params.length ? $wire[model](result, ...params) : $wire[model](result)) : $wire[model] = params.length ? [result, ...params] : result);
                live && $wire.$commit();
            });
        };

        el.addEventListener('click', listener, optionsListener);

        cleanup(() => {
            el.removeEventListener('click', listener);
        });
    });
})


document.addEventListener('livewire:init', () => {
    Livewire.on('_scUpdateBase', ([options]) => {
        for(let [key, value] of Object.entries($sc_configAlert._config)) $sc_configAlert._config[key] = options[key] ?? value;
    });

    Livewire.on('_scAlert', ([options, model]) => {
        const $wire = Livewire.first();

        let params = [];
        const checkCallFunction = model.match(/(\w+)(?:\(([^\)]*)\))/u);
        checkCallFunction && (model = checkCallFunction[1], params = JSON.parse(`[${checkCallFunction[2].replace(/\s+,\s+/g, ', ').replace(/'/g, '"')}]`));

        const title = options.title ?? '';
        const html = options.html ?? '';
        const icon = options.icon ?? undefined;
        const iconColor = options.iconColor ?? undefined;
        const position = options.position ?? undefined;
        const timer = options.timer ?? undefined;
        const showCloseButton = options.showCloseButton ?? false;
        const draggable = options.draggable ?? false;
        const grow = options.grow ?? undefined;
        const optionCustom = Object.fromEntries(Object.entries({title, html, icon, iconColor, position, timer, showCloseButton, draggable, grow}).filter(([key, value]) => Boolean(value)));

        const optionsAlert = Object.assign({}, $sc_configAlert._config, optionCustom);

        Swal.fire(optionsAlert).then(result => {
            model && (typeof $wire[model]==='function' ? (params.length ? $wire[model](result, ...params) : $wire[model](result)) : $wire[model] = params.length ? [result, ...params] : result);
            $wire.$dispatchSelf('_scResult', {result, ...options});
            $wire.$commit();
        });
    });

    Livewire.on('_scConfirm', ([options, model]) => {
        const $wire = Livewire.first();

        let params = [];
        const checkCallFunction = model.match(/(\w+)(?:\(([^\)]*)\))/u);
        checkCallFunction && (model = checkCallFunction[1], params = JSON.parse(`[${checkCallFunction[2].replace(/\s+,\s+/g, ', ').replace(/'/g, '"')}]`));

        const title = options.title ?? '';
        const html = options.html ?? '';
        const icon = options.icon ?? undefined;
        const iconColor = options.iconColor ?? undefined;
        const position = options.position ?? undefined;
        const timer = options.timer ?? undefined;
        const showDenyButton = options.showDenyButton ?? false;
        const showCancelButton = true;
        const showCloseButton = options.showCloseButton ?? false;
        const draggable = options.draggable ?? false;
        const grow = options.grow ?? undefined;
        const optionCustom = Object.fromEntries(Object.entries({title, html, icon, iconColor, position, timer, showDenyButton, showCancelButton, showCloseButton, draggable, grow}).filter(([key, value]) => Boolean(value)));

        const optionsAlert = Object.assign({}, $sc_configAlert._config, optionCustom);

        Swal.fire(optionsAlert).then(result => {
            model && (typeof $wire[model]==='function' ? (params.length ? $wire[model](result, ...params) : $wire[model](result)) : $wire[model] = params.length ? [result, ...params] : result);
            $wire.$dispatchSelf('_scResult', {result, ...options});
            $wire.$commit();
        });
    });

    Livewire.on('_scPrompt', ([options, model]) => {
        const $wire = Livewire.first();

        let params = [];
        const checkCallFunction = model.match(/(\w+)(?:\(([^\)]*)\))/u);
        checkCallFunction && (model = checkCallFunction[1], params = JSON.parse(`[${checkCallFunction[2].replace(/\s+,\s+/g, ', ').replace(/'/g, '"')}]`));

        const title = options.title ?? '';
        const html = options.html ?? '';
        const icon = options.icon ?? undefined;
        const iconColor = options.iconColor ?? undefined;
        const position = options.position ?? undefined;
        const showDenyButton = options.showDenyButton ?? false;
        const showCancelButton = true;
        const showCloseButton = options.showCloseButton ?? false;
        const draggable = options.draggable ?? false;
        const grow = options.grow ?? undefined;
        const input = options.input ?? 'text';
        let inputValue = options.inputValue ?? '';
        let inputValidator = options.inputValidator ?? undefined;
        inputValidator && (inputValidator = $wire[inputValidator]);
        let inputOptions = options.inputOptions ?? undefined;
        inputOptions && !Array.isArray(inputOptions) && (input==="select" || input==="radio") && (inputOptions = $wire.get(inputOptions));
        const optionCustom = Object.fromEntries(Object.entries({title, html, icon, iconColor, position, showDenyButton, showCancelButton, showCloseButton, draggable, grow, input, inputValue, inputValidator, inputOptions}).filter(([key, value]) => Boolean(value)));

        const optionsAlert = Object.assign({}, $sc_configAlert._config, optionCustom);

        Swal.fire(optionsAlert).then(result => {
            model && (typeof $wire[model]==='function' ? (params.length ? $wire[model](result, ...params) : $wire[model](result)) : $wire[model] = params.length ? [result, ...params] : result);
            $wire.$dispatchSelf('_scResult', {result, ...options});
            $wire.$commit();
        });
    });

    Livewire.on('_scToast', ([options, model]) => {
        const $wire = Livewire.first();

        let params = [];
        const checkCallFunction = model.match(/(\w+)(?:\(([^\)]*)\))/u);
        checkCallFunction && (model = checkCallFunction[1], params = JSON.parse(`[${checkCallFunction[2].replace(/\s+,\s+/g, ', ').replace(/'/g, '"')}]`));

        const title = options.title ?? '';
        const toast = true;
        const icon = options.icon ?? undefined;
        const iconColor = options.iconColor ?? undefined;
        const position = options.position ?? undefined;
        const timer = options.timer ?? undefined;
        const timerProgressBar = options.timerProgressBar ?? false;
        const showConfirmButton = false;
        const showCloseButton = options.showCloseButton ?? false;
        const optionCustom = Object.fromEntries(Object.entries({title, toast, icon, iconColor, position, timer, timerProgressBar, showCloseButton}).filter(([key, value]) => Boolean(value)));
        optionCustom['showConfirmButton'] = showConfirmButton;

        const optionsAlert = Object.assign({}, $sc_configAlert._config, optionCustom);
        delete optionsAlert['backdrop']; delete optionsAlert['keydownListenerCapture']; delete optionsAlert['returnFocus'];

        Swal.fire(optionsAlert).then(result => {
            model && (typeof $wire[model]==='function' ? (params.length ? $wire[model](result, ...params) : $wire[model](result)) : $wire[model] = params.length ? [result, ...params] : result);
            $wire.$dispatchSelf('_scResult', {result, ...options});
            $wire.$commit();
        });
    });

    Livewire.on('_scProgress', ([options, model]) => {
        const $wire = Livewire.first();

        let params = [];
        const checkCallFunction = model.match(/(\w+)(?:\(([^\)]*)\))/u);
        checkCallFunction && (model = checkCallFunction[1], params = JSON.parse(`[${checkCallFunction[2].replace(/\s+,\s+/g, ', ').replace(/'/g, '"')}]`));

        const title = options.title ?? '';
        const html = options.html ?? '';
        const icon = options.icon ?? undefined;
        const iconColor = options.iconColor ?? undefined;
        const position = options.position ?? undefined;
        const timer = options.timer ?? undefined;
        const timerProgressBar = true;
        const showConfirmButton = options.showConfirmButton ?? true;
        const showDenyButton = options.showDenyButton ?? false;
        const showCancelButton = options.showCancelButton ?? false;
        const showCloseButton = options.showCloseButton ?? false;
        const draggable = options.draggable ?? false;
        const grow = options.grow ?? undefined;
        const optionCustom = Object.fromEntries(Object.entries({title, html, icon, iconColor, position, timer, timerProgressBar, showDenyButton, showCancelButton, showCloseButton, draggable, grow}).filter(([key, value]) => Boolean(value)));
        optionCustom['showConfirmButton'] = showConfirmButton;

        const optionsAlert = Object.assign({}, $sc_configAlert._config, optionCustom);

        Swal.fire(optionsAlert).then(result => {
            model && (typeof $wire[model]==='function' ? (params.length ? $wire[model](result, ...params) : $wire[model](result)) : $wire[model] = params.length ? [result, ...params] : result);
            $wire.$dispatchSelf('_scResult', {result, ...options});
            $wire.$commit();
        });
    });
});
