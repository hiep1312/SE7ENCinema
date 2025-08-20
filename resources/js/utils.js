import Sortable from "sortablejs";

window.scUtils = {
    /**
     * Gắn (thêm) một listener (trình xử lý sự kiện) vào phần tử HTML.
     *
     * @param {HTMLElement} el - Phần tử HTML cần gắn sự kiện.
     * @param {string} event - Tên sự kiện (ví dụ: 'click', 'drag',...).
     * @param {Function} fn - Hàm callback sẽ được gọi khi sự kiện xảy ra.
     */
    on: Sortable.utils.on,

    /**
     * Gỡ (loại bỏ) một listener (trình xử lý sự kiện) khỏi phần tử HTML.
     *
     * @param {HTMLElement} el - Phần tử HTML cần gỡ sự kiện.
     * @param {string} event - Tên sự kiện cần gỡ (ví dụ: 'click', 'drag',...).
     * @param {Function} fn - Hàm callback trước đó đã được gắn với sự kiện này.
     */
    off: Sortable.utils.off,

    /**
     * Đọc hoặc ghi thuộc tính CSS của một phần tử HTML.
     *
     * @param {HTMLElement} el - Phần tử HTML cần thao tác.
     * @param {string|object} prop - Tên thuộc tính CSS (ví dụ: 'width', 'backgroundColor'). Hoặc một đối tượng chứa các giá trị CSS sẽ thay thế hoặc thêm vào.
     * @param {string|number} [val] - Giá trị cần đặt. Nếu không truyền, hàm sẽ trả về giá trị hiện tại.
     * @returns {string|CSSStyleDeclaration|undefined} Giá trị thuộc tính hoặc toàn bộ style hiện tại nếu không truyền prop.
     */
    css: Sortable.utils.css,

    /**
     * Tìm tất cả phần tử con theo tagName trong một context (ctx), và có thể gọi hàm lặp trên từng phần tử nếu muốn.
     *
     * @param {HTMLElement} ctx - Phần tử HTML cha để tìm kiếm bên trong.
     * @param {string} tagName - Tên thẻ cần tìm (ví dụ: 'div', 'span').
     * @param {(el: HTMLElement, index: number) => void} [iterator] - Hàm gọi trên mỗi phần tử tìm được (tùy chọn).
     * @returns {HTMLCollection | []} Danh sách các phần tử tìm được, hoặc mảng rỗng nếu ctx không tồn tại.
     */
    find: Sortable.utils.find,

    /**
     * Nhận vào một hàm và trả về một hàm mới luôn được gọi với ngữ cảnh (`this`) xác định.
     *
     * @param {Mixed} ctx - Ngữ cảnh (context) mà hàm sẽ luôn sử dụng khi được gọi (ví dụ: `this` trong hàm).
     * @param {Function} fn - Hàm gốc cần bind.
     * @returns {Function} Hàm mới với ngữ cảnh đã bind.
     */
    bind: Sortable.utils.bind,

    /**
     * Kiểm tra phần tử `el` có khớp với selector đã cho hay không.
     *
     * @param {HTMLElement} el - Phần tử HTML cần kiểm tra.
     * @param {string} selector - Selector CSS cần so sánh.
     * @returns {boolean} Trả về `true` nếu phần tử khớp với selector, ngược lại là `false`.
     */
    is: Sortable.utils.is,

    /**
     * Tìm phần tử tổ tiên gần nhất (hoặc chính nó) khớp với selector đã cho.
     *
     * @param {HTMLElement} el - Phần tử bắt đầu tìm kiếm.
     * @param {string} selector - Selector CSS dùng để kiểm tra.
     * @param {HTMLElement} [ctx=document] - Phạm vi (context) để dừng tìm (mặc định là `document`).
     * @param {boolean} [includeCTX=false] - Nếu true, phần tử ctx cũng được xem xét so khớp.
     * @returns {HTMLElement|null} Phần tử tìm được hoặc null nếu không có.
     */
    closest: Sortable.utils.closest,

    /**
     * Tạo một bản sao (clone) của phần tử HTML `el`.
     * Hỗ trợ Polymer và jQuery/Zepto nếu có, nếu không sẽ dùng `cloneNode(true)` chuẩn.
     *
     * @param {HTMLElement} el - Phần tử cần sao chép.
     * @returns {HTMLElement} Bản sao của phần tử truyền vào.
     */
    clone: Sortable.utils.clone,

    /**
     * Thêm hoặc xóa một class khỏi phần tử HTML, tùy vào trạng thái `state`.
     *
     * @param {HTMLElement} el - Phần tử cần thay đổi class.
     * @param {string} name - Tên class cần thêm hoặc xóa.
     * @param {boolean} state - Nếu true thì thêm class, false thì xóa.
     */
    toggleClass: Sortable.utils.toggleClass,

    /**
     * Trả về chỉ số (index) của phần tử `el` trong danh sách các phần tử con của cha nó.
     * Có thể lọc theo selector để chỉ tính những phần tử phù hợp.
     *
     * @param {HTMLElement} el - Phần tử cần lấy chỉ số.
     * @param {string} [selector] - Selector để lọc các phần tử tính chỉ số (tùy chọn).
     * @returns {number} Vị trí của phần tử `el` trong danh sách, hoặc -1 nếu không tìm thấy.
     */
    index: Sortable.utils.index,

    /**
     * Lấy phần tử con thứ `childNum` trong phần tử `el`, **bỏ qua** các phần tử bị ẩn.
     *
     * @param {HTMLElement} el - Phần tử cha.
     * @param {number} childNum - Chỉ số (index) phần tử con muốn lấy.
     * @returns {HTMLElement|null} Phần tử con hợp lệ tại vị trí `childNum`, hoặc `null` nếu không có.
     */
    getChild: Sortable.utils.getChild,

    /**
     * Lấy phần tử con **cuối cùng** trong `el`, bỏ qua các phần tử bị ẩn và không khớp selector.
     *
     * @param {HTMLElement} el - Phần tử cha cần tìm con cuối.
     * @param {string} [selector] - Selector để lọc các phần tử con hợp lệ (tùy chọn).
     * @returns {HTMLElement|null} Phần tử con cuối hợp lệ, hoặc null nếu không có.
     */
    lastChild: Sortable.utils.lastChild,
}

Object.freeze(scUtils);
