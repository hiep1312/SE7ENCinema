let dataFoods = [];
let currentSelection = {};

function updateAvailableVariants(clearOldSelection = false){
    const dataFoodId = Object.keys(currentSelection)[0];
    const dataAttr = clearOldSelection ? [] : Object.keys(currentSelection[dataFoodId]);
    let availableListAttrValues = {};

    if(dataAttr.length > 1){
        const variants = dataFoods.find((food) => food.id === Number(dataFoodId)).variants.filter((variant) => {
            for(const attr of dataAttr) if(variant.attributes[attr] !== currentSelection[dataFoodId][attr]) return false;

            return true;
        });

        variants.forEach(variant => {
            for(const [attr, value] of Object.entries(variant.attributes)){
                !availableListAttrValues[attr] && (availableListAttrValues[attr] = new Set());
                availableListAttrValues[attr].add(value);
            }
        });
    }else{
        for(const attr of dataFoods.find((food) => food.id === Number(dataFoodId)).attributes) availableListAttrValues[attr.name] = new Set(attr.values.map(value => value.value));
    }

    console.log(currentSelection);

    for(const [attr, values] of Object.entries(availableListAttrValues)){
        const attrValues = document.querySelectorAll(`[data-food-id='${dataFoodId}'] [data-attribute='${attr}'] [data-value]`);
        attrValues.forEach(attrValue =>  attrValue.classList.toggle('unavailable', !values.has(attrValue.dataset.value)));
    }
}

document.addEventListener('livewire:init', async () => {
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
});
