import Swal from "sweetalert2";

let dataFoods = [];
let currentSelection = {};
let cartTempVariantId = null;

function getCurrentSelection(){
    const dataFoodId = Object.keys(currentSelection)[0];
    const dataAttr = Object.keys(currentSelection[dataFoodId] ?? []);

    return [dataFoodId, dataAttr];
}

function filterInvalidVariantAttributes(){
    const [dataFoodId, dataAttr] = getCurrentSelection();
    let dataFood;

    if(dataFoodId && dataAttr.length > 0 && (dataFood = dataFoods.find(food => food.id === +dataFoodId)))
        dataAttr.forEach(attr => {
            if(!dataFood.availableAttributes[attr]) delete currentSelection[dataFoodId][attr];

            const attrValue = currentSelection[dataFoodId][attr];
            if(!attrValue || !dataFood.availableAttributes[attr].includes(attrValue)) delete currentSelection[dataFoodId][attr];
        });
    else currentSelection = {};
}

function updateAvailableVariants(clearOldSelection = false){
    filterInvalidVariantAttributes();

    let [dataFoodId, dataAttr] = getCurrentSelection();
    clearOldSelection && (dataAttr = []);
    let availableListAttrValues = {};

    /* Setup data */
    document.querySelectorAll(`[data-food-id] .booking-food-auto-flip-btn.show`).forEach(button => button.classList.remove('show'));
    document.querySelectorAll(`[data-food-id].flipped`).forEach(card => card.classList.remove('flipped'));
    cartTempVariantId && (cartTempVariantId = null);
    const updatePrice = (priceOrVariants) => {
        let priceText = Array.isArray(priceOrVariants) ? '' : priceOrVariants?.toLocaleString('vi').concat('đ');
        if(Array.isArray(priceOrVariants)){
            const listPrice = priceOrVariants.map(variant => variant?.price);
            priceText = `${Math.min(...listPrice).toLocaleString('vi').concat('đ')} - ${Math.max(...listPrice).toLocaleString('vi').concat('đ')}`;
        }

        document.querySelector(`[data-food-id='${dataFoodId}'] .booking-food-price-value`).textContent = priceText;
    };

    if(dataAttr.length > 0){
        const variants = dataFoods.find(food => food.id === +dataFoodId).variantsData.filter(variant => {
            for(const attr of dataAttr) if(variant.attributes[attr] !== currentSelection[dataFoodId][attr]) return false;

            return true;
        });

        if(variants.length > 0){
            variants.forEach(variant => {
                for(const [attr, value] of Object.entries(variant.attributes)){
                    !availableListAttrValues[attr] && (availableListAttrValues[attr] = new Set());
                    availableListAttrValues[attr].add(value);
                }
            });
            variants.length === 1 && (document.querySelector(`[data-food-id='${dataFoodId}'] .booking-food-auto-flip-btn`).classList.add('show') || (document.querySelector(`[data-food-id='${dataFoodId}'] .booking-food-auto-flip-btn`).disabled = !Boolean(variants[0].quantity_available)) || (cartTempVariantId = variants[0].id));
            updatePrice(variants.length === 1 ? variants[0].price : variants);
        }else{
            delete currentSelection[dataFoodId][dataAttr.at(-1)];
            Swal.fire('Xin lỗi!', 'Sản phẩm với thuộc tính đã chọn hiện không khả dụng.', 'error');
            updateAvailableVariants();
            return;
        }
    }else{
        const dataFood = dataFoods.find(food => food.id === +dataFoodId);
        availableListAttrValues = dataFood?.availableAttributes;
        updatePrice(dataFood.variantsData);
    }

    for(const [attr, values] of Object.entries(availableListAttrValues)){
        const attrValues = document.querySelectorAll(`[data-food-id='${dataFoodId}'] [data-attribute='${attr}'] [data-value]`);
        attrValues.forEach(attrValue => {
            attrValue.classList.toggle('unavailable', Array.isArray(values) ? !values.includes(attrValue.dataset.value) : !values.has(attrValue.dataset.value));
            attrValue.style.display = ((values?.length > 1 && !values.includes(attrValue.dataset.value)) || (values?.size > 1 && !values.has(attrValue.dataset.value))) ? "none" : "flex";
        });
    }
}

document.addEventListener('livewire:init', () => {
    window.setDataFoods = function (foods){
        dataFoods = foods;
    };

    window.selectVariant = function(e){
        const dataAttrValue = e.target.dataset.value;
        const dataAttr = e.target.closest("[data-attribute]").dataset.attribute;
        const dataFoodId = e.target.closest("[data-food-id]").dataset.foodId;

        if(dataFoodId && dataAttr && dataAttrValue){
            const isReactivating = currentSelection?.[dataFoodId]?.[dataAttr] === dataAttrValue;
            const attrValueSiblings = Array.from(e.target.parentElement.children);
            attrValueSiblings.forEach(attrValueSibling => attrValueSibling.classList.toggle('active', attrValueSibling === e.target && !isReactivating));

            if(isReactivating){
                delete currentSelection[dataFoodId][dataAttr];
            }else{
                const attrValueOthers = document.querySelectorAll(`[data-food-id]:not([data-food-id='${dataFoodId}']) [data-value]`);
                attrValueOthers.forEach(attrValueOther => attrValueOther.classList.remove('active'));

                Object.keys(currentSelection).length !== 0 && !currentSelection[dataFoodId] && (updateAvailableVariants(true) || (currentSelection = {}));
                (currentSelection[dataFoodId] || (currentSelection[dataFoodId] = {})) && (currentSelection[dataFoodId][dataAttr] = dataAttrValue);
            }

            updateAvailableVariants();
        }
    }

    window.toggleTempCartItem = function(buttonAction, addCart = false){
        const $wire = Livewire.find(document.querySelector("[wire\\:id][sc-root]")?.getAttribute('wire:id'));
        const card = buttonAction.closest('.booking-food-item-card');

        if(Object.keys(currentSelection)?.[0] === card.dataset.foodId) $wire.$set('cartTempVariantId', addCart ? cartTempVariantId : null, true);
        card.classList.toggle('flipped', addCart);
    }

    Livewire.on('reservationExpired', function(redirectUrl){
        Swal.fire({
            title: "Hết thời gian giữ ghế",
            html: "Vui lòng chọn lại suất chiếu và ghế để tiếp tục đặt vé",
            icon: 'info',
            iconColor: '#ffbb33',
        }).then(() => {
            const redirectEl = document.createElement('a');
            redirectEl.href = redirectUrl;
            redirectEl.click();
        });
    });
});
