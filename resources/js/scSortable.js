import Sortable from 'sortablejs';

/**
 * @typedef {Object} scSortable
 * @property {Object} options - Đối tượng cấu hình của một thể hiện của Sortable.
 * @property {function(): string[]} toArray - Trả về mảng các ID (dưới dạng chuỗi) của các phần tử dragged trong sortable.
 * @property {function(string[], boolean=): void} sort - Sắp xếp lại thứ tự phần tử dragged theo mảng ID, tùy chọn dùng hiệu ứng.
*/

const $sc_configSortable = {
    /**
     * Đối tượng chứa danh sách các sortable được quản lý, khóa là ID, giá trị là đối tượng scSortable.
     *
     * @type {Object.<string, scSortable>}
     */
    _manage: {},
    _config: {
        delay: 0,
        delayOnTouchOnly: false,
        store: {
            /* get: function (sortable) {
                const order = localStorage.getItem(sortable.options.group.name);
                return order ? order.split('<$sc|>') : [];
            },
            set: function (sortable) {
                const order = sortable.toArray();
                localStorage.setItem(sortable.options.group.name, order.join('<$sc|>'));
            } */
        },
        animation: 150,
        easing: null,
        preventOnFilter: true,
        dataIdAttr: 'sc-id',
        ghostClass: 'sortable-ghost',
        chosenClass: 'sortable-chosen',
        dragClass: 'sortable-drag',
        ignore: 'a, img',
        swapThreshold: 1,
        invertSwap: false,
        invertedSwapThreshold: null,
        dropBubble: false,
        dragoverBubble: false,
        emptyInsertThreshold: 10,
    },
    _validators: {
        group_expression: function (value){
            const result = /\{(?:[^}]*)\}/u.test(value) ? JSON.parse(value.replace(/\s+,\s+/g, ', ').replace(/'/g, '"')) : value;
            return result;
        },
        sort_directive: function (directive){
            const {modifiers} = directive;
            return !modifiers.includes('sort');
        },
        disabled_directive: function (directive){
            const {modifiers} = directive;
            return modifiers.includes('disabled');
        },
        onSort_directive: function (directive){
            const {raw} = directive;
            const onSort = raw.match(/\.onsort\.([^\.]+)/);
            return onSort && onSort[1];
        },
        onMove_directive: function (directive){
            const {raw} = directive;
            const onMove = raw.match(/\.onmove\.([^\.]+(?:\.[^\.]+)?)/);
            return onMove && onMove[1];
        },
        checkFunction: function (model, $wire){
            return (model || false) && ($wire || typeof Livewire.first()[model] === 'function') && typeof $wire[model]==='function';
        }
    },
    _packageObjectEvent: function (event){
        if(!(event instanceof CustomEvent)) return typeof event === 'object' ? Object.fromEntries(event) : event;

        return {
            to: event?.to.dataset.row,
            from: event?.from.dataset.row,
            item: event?.item?.dataset.id,
            clone: event?.clone?.dataset.id,
            oldIndex: event?.oldIndex,
            newIndex: event?.newIndex,
            oldDraggableIndex: event?.oldDraggableIndex,
            newDraggableIndex: event?.newDraggableIndex,
            pullMode: event?.pullMode,
            dragged: event?.dragged?.dataset.id,
            draggedRect: event?.draggedRect && {
                top: event.draggedRect.top,
                left: event.draggedRect.left,
                right: event.draggedRect.right,
                bottom: event.draggedRect.bottom,
                width: event.draggedRect.width,
                height: event.draggedRect.height
            },
            related: event?.related?.dataset.id,
            relatedRect: event?.relatedRect && {
                top: event.relatedRect.top,
                left: event.relatedRect.left,
                right: event.relatedRect.right,
                bottom: event.relatedRect.bottom,
                width: event.relatedRect.width,
                height: event.relatedRect.height
            },
            willInsertAfter: event?.willInsertAfter
        };
    }
};

window.scSortable = {
    /**
     * Lấy thể hiện của Sortable dựa vào phần tử hoặc ID của phần tử.
     *
     * @param {string|HTMLElement} element - Một chuỗi đại diện cho ID, selector hoặc một phần tử HTML.
     * @returns {Sortable|null} - Trả về thể hiện Sortable nếu tìm thấy, ngược lại trả về null.
     *
     * @description
     * - Nếu `element` là chuỗi, nó sẽ được dùng như ID để tra cứu trong hệ thống quản lý `$sc_configSortable._manage`.
     * - Nếu `element` là một phần tử `HTMLElement`, sẽ gọi `Sortable.get(element)` để lấy thể hiện.
     * - Nếu không phải chuỗi hoặc HTMLElement, trả về `null`.
     *
     * @example
     * let sortable1 = $sc_configSortable.get('my-list-id');
     * let sortable2 = $sc_configSortable.get(document.getElementById('my-list-id'));
     */
    get: function(element){
        if(!(element instanceof HTMLElement) && typeof element !== 'string') return null;
        let checkExistsInManage;
        typeof element === "string" && (checkExistsInManage = $sc_configSortable._manage[element]);

        return checkExistsInManage ?? Sortable.get(element instanceof HTMLElement ? element : document.querySelector(element));
    },

    /**
     * Thể hiện Sortable hiện tại đang hoạt động (được kéo hoặc đang xử lý).
     * @type {Sortable|null}
     */
    active: Sortable.active,

    /**
     * Phần tử HTML đang được kéo.
     * @type {HTMLElement|null}
     */
    dragged: Sortable.dragged,

    /**
     * Phần tử bóng (ghost) được hiển thị khi kéo phần tử.
     * @type {HTMLElement|null}
     */
    ghost: Sortable.ghost,

    /**
     * Bản sao (clone) của phần tử đang được kéo, dùng để giữ nguyên bố cục khi kéo.
     * @type {HTMLElement|null}
     */
    clone: Sortable.clone
};

Object.seal($sc_configSortable);
Object.freeze(window.scSortable);

document.addEventListener('livewire:init', () => {
    Livewire.directive('sc-sortable', function ({ $wire, el, directive }){
        /* Set config */
        el.hasAttribute('wire:ignore') || el.setAttribute('wire:ignore', '');

        const modelAtrribute = Array.from(el.attributes).find(attr => attr.name.startsWith('wire:sc-model'));
        /* Get directive & model */
        const model = modelAtrribute && modelAtrribute.value;
        const rawDirectiveModel = (modelAtrribute || '') && modelAtrribute.name.slice('wire:sc-model'.length);
        const modifiersModel = rawDirectiveModel.slice(1).split('.');
        /* Directive */
        const live = modifiersModel.includes('live');
        const debounce = rawDirectiveModel.match(/\.debounce\.([^\.]+)ms/)?.[1];

        const {expression} = directive;
        const group = expression ? $sc_configSortable._validators.group_expression(expression) : null;
        const handle = el.getAttribute('wire:sc-handle');
        const filter = el.getAttribute('wire:sc-filter');

        /* Modifier Options */
        const sort = $sc_configSortable._validators.sort_directive(directive);
        const disabled = $sc_configSortable._validators.disabled_directive(directive);

        let timeout;
        const onSort = function(evt){
            const callback = $sc_configSortable._validators.onSort_directive(directive);
            let result = null;
            typeof window[callback] === 'function' && (result = window[callback](evt));
            result !== null || ($sc_configSortable._validators.checkFunction(callback, $wire) && $wire[callback]($sc_configSortable._packageObjectEvent(evt)));
            clearTimeout(timeout);
            timeout = setTimeout(() => {
                model &&
                typeof $wire[model] !== 'function' &&
                ((el.getAttribute('sc-group') && Array.isArray($sc_configSortable._manage[el.getAttribute('sc-group')])) ? $wire.$set(model, $sc_configSortable._manage[el.getAttribute('sc-group')].flatMap((sortable) => {
                    return sortable.toArray();
                }), live) : $wire.$set(model, sortable.toArray(), live));
            }, debounce ?? 0);
            console.log($sc_configSortable._manage[el.getAttribute('sc-group')]);
        };
        const onMove = function(evt){
            let callback = $sc_configSortable._validators.onMove_directive(directive);
            callback && (callback = callback.split('.'));
            let result = null;
            if(callback){
                for(let i = 0; i < callback.length; i++){
                    typeof window[callback[i]] === 'function' && (result = window[callback[i]](evt));
                    result !== null || ($sc_configSortable._validators.checkFunction(callback[i], $wire) && $wire[callback[i]]($sc_configSortable._packageObjectEvent(evt)));
                }
            }
            return result ?? function(evt){
                if(evt.to.classList.contains('disabled') && evt.to !== evt.from) return false;
                else if(evt.related.classList.contains('disabled') && (evt.related.classList.contains('before') || evt.related.classList.contains('after'))) return evt.related.classList.contains('before') ? -1 : 1;
                return void 0;
            }(evt);
        }
        const optionsCustom = Object.fromEntries(Object.entries({group, handle, filter, disabled, onSort, onMove}).filter(([, value]) => Boolean(value)));
        optionsCustom['sort'] = sort;

        const options = Object.assign({}, $sc_configSortable._config, optionsCustom);
        const sortable = Sortable.create(el, options);

        el.getAttribute('sc-group') &&
        ($sc_configSortable._manage[el.getAttribute('sc-group')] || ($sc_configSortable._manage[el.getAttribute('sc-group')] = sortable) && void 0) &&
        ($sc_configSortable._manage[el.getAttribute('sc-group')] = Array.isArray($sc_configSortable._manage[el.getAttribute('sc-group')]) ? [...$sc_configSortable._manage[el.getAttribute('sc-group')], sortable] : [$sc_configSortable._manage[el.getAttribute('sc-group')], sortable]);
    });
});
