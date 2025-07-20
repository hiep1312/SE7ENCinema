<div>
    <div class="info">
        <style>
            #preloader {
                display: none;
            }

            .popup-overlay {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(0, 0, 0, 0.6);
                display: flex;
                align-items: center;
                justify-content: center;
                z-index: 1000;
                opacity: 1;
                backdrop-filter: blur(5px);
            }

            .popup-container {
                background: white;
                border-radius: 15px;
                box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
                width: 90%;
                max-width: 550px;
                position: relative;
            }

            /* Popup Header */
            .popup-header {
                background: linear-gradient(135deg, #ff4757 0%, #ff3742 100%);
                color: white;
                padding: 25px 30px;
                border-radius: 15px 15px 0 0;
                position: relative;
                text-align: center;
            }

            .popup-title {
                font-size: 24px;
                font-weight: bold;
                margin: 0;
                display: flex;
                align-items: center;
                justify-content: center;
                gap: 10px;
            }

            .popup-subtitle {
                font-size: 14px;
                opacity: 0.9;
                margin-top: 5px;
            }

            .close-btn {
                position: absolute;
                top: 15px;
                right: 15px;
                background: rgba(255, 255, 255, 0.2);
                border: none;
                color: white;
                width: 35px;
                height: 35px;
                border-radius: 50%;
                cursor: pointer;
                font-size: 18px;
                display: flex;
                align-items: center;
                justify-content: center;
            }

            .close-btn:hover {
                background: rgba(255, 255, 255, 0.3);
            }

            /* Popup Body */
            .popup-body {
                padding: 35px 30px;
            }

            /* Form Styles */
            .form-group {
                margin-bottom: 25px;
                position: relative;
            }

            .form-label {
                display: block;
                font-weight: 600;
                color: #333;
                margin-bottom: 8px;
                font-size: 14px;
            }

            .required {
                color: #ff4757;
                margin-left: 3px;
            }

            .form-input {
                width: 100%;
                padding: 15px 50px 15px 15px;
                border: 2px solid #e9ecef;
                border-radius: 10px;
                font-size: 16px;
                transition: all 0.3s ease;
                background: #f8f9fa;
                color: #333;
            }

            .form-input:focus {
                outline: none;
                border-color: #ff4757;
                background: white;
                box-shadow: 0 0 0 3px rgba(255, 71, 87, 0.1);
            }

            /* Password Toggle */
            .password-toggle {
                position: absolute;
                right: 15px;
                top: 20%;
                background: none;
                border: none;
                cursor: pointer;
                font-size: 18px;
                color: #666;
                padding: 5px;
                border-radius: 5px;
                transition: all 0.3s ease;
            }

            .password-toggle:hover {
                background: #f0f0f0;
                color: #333;
            }

            .input-wrapper {
                position: relative;
            }

            /* Error Message */
            .error-message {
                color: #dc3545;
                font-size: 15px;
                margin-top: 5px;
                display: flex;
                align-items: center;
                gap: 5px;
                opacity: 1;
                transform: translateY(0);
                transition: all 0.3s ease;
            }

            /* Popup Footer */
            .popup-footer {
                padding: 0 30px 0px;
                display: flex;
                gap: 15px;
                justify-content: flex-end;
            }

            .btn {
                padding: 12px 25px;
                border: none;
                border-radius: 8px;
                font-size: 14px;
                font-weight: 600;
                cursor: pointer;
                transition: all 0.3s ease;
                display: flex;
                align-items: center;
                gap: 8px;
                min-width: 120px;
                justify-content: center;
            }

            .btn-cancel {
                background: #6c757d;
                color: white;
            }

            .btn-cancel:hover {
                background: #5a6268;
                color: white;
            }

            .btn-changePass {
                background: #FF4444;
                color: white;
                position: relative;
                overflow: hidden;
            }

            .btn-changePass:hover {
                background: #f21628;
                color: white;
            }

            /* Responsive Design */
            @media (max-width: 768px) {
                .popup-container {
                    width: 95%;
                    margin: 20px;
                }

                .popup-header {
                    padding: 20px;
                }

                .popup-title {
                    font-size: 20px;
                }

                .popup-body {
                    padding: 25px 20px;
                }

                .popup-footer {
                    padding: 0 20px 25px;
                    flex-direction: column;
                }

                .btn {
                    width: 100%;
                }

                .form-input {
                    padding: 12px 45px 12px 12px;
                    font-size: 16px;
                    /* Prevent zoom on iOS */
                }
            }

            @media (max-width: 480px) {
                .popup-container {
                    width: 100%;
                    height: 100%;
                    border-radius: 0;
                    max-height: 100vh;
                }

                .popup-header {
                    border-radius: 0;
                }
            }

            .info {

                .food-item {
                    display: flex;
                    justify-content: space-between;
                    align-items: flex-start;
                    padding: 0.5rem 0;
                    border-bottom: 1px solid #e9ecef;
                }

                .food-item:last-child {
                    border-bottom: none;
                }

                .food-info {
                    flex: 1;
                }

                .food-name-row {
                    display: flex;
                    align-items: center;
                    gap: 0.5rem;
                    margin-bottom: 0.25rem;
                }

                .food-name {
                    color: #1f2937;
                    font-weight: 500;
                }

                .quantity-badge {
                    background-color: #f3f4f6;
                    color: #6b7280;
                    padding: 0.125rem 0.375rem;
                    border-radius: 4px;
                    font-size: 0.75rem;
                    border: 1px solid #d1d5db;
                }

                .variants {
                    color: #6b7280;
                    font-size: 0.75rem;
                    margin-bottom: 0.25rem;
                    line-height: 1.4;
                }

                .price-detail {
                    color: #6b7280;
                    font-size: 0.875rem;
                }

                .food-total {
                    font-weight: 500;
                    margin-left: 610px;
                    color: #1f2937;
                    text-align: right;
                }

                .pagination1 {
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    gap: 8px;
                    margin: 2rem 0;
                    flex-wrap: wrap;
                }

                .pagination1 .page-item1 {
                    list-style: none;
                }

                .pagination1 .page-link1 {
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    min-width: 40px;
                    height: 40px;
                    padding: 8px 12px;
                    text-decoration: none;
                    color: #6b7280;
                    background-color: #ffffff;
                    border: 1px solid #e5e7eb;
                    border-radius: 8px;
                    font-size: 14px;
                    font-weight: 500;
                    transition: all 0.2s ease-in-out;
                    position: relative;
                    overflow: hidden;
                }

                .pagination1 .page-link1:hover {
                    color: #3b82f6;
                    background-color: #f8fafc;
                    border-color: #3b82f6;
                    transform: translateY(-1px);
                    box-shadow: 0 4px 12px rgba(59, 130, 246, 0.15);
                }

                .pagination1 .page-item1.active .page-link1 {
                    color: #ffffff;
                    background: linear-gradient(135deg, #3b82f6, #1d4ed8);
                    border-color: #3b82f6;
                    box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
                }

                .pagination1 .page-item1.disabled .page-link1 {
                    color: #d1d5db;
                    background-color: #f9fafb;
                    border-color: #e5e7eb;
                    cursor: not-allowed;
                    transform: none;
                }

                .pagination1 .page-item1.disabled .page-link1:hover {
                    color: #d1d5db;
                    background-color: #f9fafb;
                    border-color: #e5e7eb;
                    transform: none;
                    box-shadow: none;
                }


                background-color: #EFF1F5 !important;

                .alertSuccess {
                    position: relative;
                    padding: 0.75rem 1.25rem;
                    margin-bottom: 1rem;
                    border: 1px solid transparent;
                    border-radius: 0.25rem;
                    font-size: 1rem;
                    color: black;
                    background-color: #D1E7DD;
                }

                .status-completed {
                    background: rgb(22 163 74 / 1);
                    color: white;
                }

                .status-pending {
                    background: #f39c12;
                    color: white;
                }

                .movie-poster {
                    width: 130px;
                    border-radius: 8px;
                    object-fit: cover;
                    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
                }

                .movie-title {
                    font-size: 20px;
                    font-weight: bold;
                    color: #333;
                    margin-bottom: 15px;
                }

                .fix {
                    clear: both;
                }

                .invalid-feedback {
                    width: 100%;
                    margin-top: 0.25rem;
                    font-size: 0.875em;
                    color: #dc3545;
                }

                .main-content {
                    max-width: 1425px;
                    margin: 0 auto;
                    padding: 30px 20px;
                }

                .profile-container {
                    display: grid;
                    grid-template-columns: 350px 1fr;
                    gap: 30px;
                    background: white;
                    border-radius: 10px;
                    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
                    overflow: hidden;
                }

                .profile-sidebar {
                    background: #f8f9fa;
                    padding: 30px;
                    border-right: 1px solid #e9ecef;
                }

                .profile-avatar {
                    text-align: center;
                    margin-bottom: 20px;
                }

                .avatar-circle {
                    width: 120px;
                    height: 120px;
                    border-radius: 50%;
                    background: linear-gradient(135deg, #ddd 0%, #bbb 100%);
                    margin: 0 auto 15px;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    font-size: 48px;
                    color: white;
                    position: relative;
                    overflow: hidden;
                }

                .avatar-circle img {
                    width: 100%;
                    height: 100%;
                    object-fit: cover;
                    object-position: center;
                    display: block;
                    position: relative;
                    z-index: 1;
                }

                .avatar-circle::before {
                    content: "👤";
                    font-size: 60px;
                    opacity: 0.3;
                    position: absolute;
                    z-index: 0;
                }

                .profile-name {
                    font-size: 22px;
                    font-weight: bold;
                    color: #333;
                    margin-bottom: 5px;
                }

                .profile-join-date {
                    color: #666;
                    font-size: 14px;
                }

                .profile-info {
                    margin-top: 20px;
                }

                .info-item {
                    display: flex;
                    justify-content: space-between;
                    padding: 12px 0;
                    border-bottom: 1px solid #e9ecef;
                }

                .info-label {
                    font-weight: 500;
                    color: #333;
                }

                .info-value {
                    color: #666;
                    text-wrap: balance;
                    max-inline-size: 17ch;
                    text-align: right;
                }

                .profile-main {
                    padding: 0;
                }

                .tab-header {
                    display: flex;
                    background-color: #f8f9fa;
                    border-bottom: 1px solid #dee2e6;
                }

                .tab-item {
                    padding: 15px 25px;
                    background-color: transparent;
                    border: none;
                    cursor: pointer;
                    font-size: 14px;
                    color: #6c757d;
                    border-right: 1px solid #dee2e6;
                    transition: all 0.3s;
                    text-transform: uppercase;
                    font-weight: 500;
                }

                .tab-item:hover {
                    background-color: #e9ecef;
                }

                .tab-item.active {
                    background: linear-gradient(135deg, #ff6060 0%, #fe2e2e 100%);
                    color: white;
                }

                .form-container {
                    padding: 40px;
                }

                .upload-section {
                    text-align: center;
                    margin-bottom: 40px;
                    padding: 30px;
                    background: #f8f9fa;
                    border-radius: 10px;
                    border: 2px dashed #dee2e6;
                }

                .upload-buttons {
                    display: flex;
                    justify-content: center;
                    gap: 15px;
                    margin-bottom: 15px;
                }

                .btn {
                    padding: 12px 24px;
                    border: none;
                    border-radius: 6px;
                    cursor: pointer;
                    font-size: 14px;
                    font-weight: 500;
                    text-transform: uppercase;
                    transition: all 0.3s;
                }

                .btn-upload {
                    background: linear-gradient(135deg, #6c757d 0%, #5a6268 100%);
                    color: white;
                }

                .btn-upload:hover {
                    transform: translateY(-2px);
                    box-shadow: 0 4px 15px rgba(108, 117, 125, 0.3);
                }

                .form-grid {
                    display: grid;
                    grid-template-columns: 1fr 1fr;
                    gap: 25px;
                    margin-bottom: 25px;
                }

                .form-group {
                    display: flex;
                    flex-direction: column;
                }

                .booking-actions {
                    display: flex;
                    gap: 8px;
                    margin-top: 15px;
                    flex-wrap: wrap;
                }

                .action-btn {
                    padding: 6px 12px;
                    border: none;
                    border-radius: 6px;
                    font-size: 12px;
                    font-weight: 500;
                    cursor: pointer;
                    transition: all 0.3s;
                    display: flex;
                    align-items: center;
                    gap: 4px;
                }

                .btn-detail {
                    background: #007bff;
                    color: white;
                }

                .btn-detail:hover {
                    background: #0056b3;
                }

                .btn-rate {
                    background: #ffc107;
                    color: #212529;
                }

                .btn-rate:hover {
                    background: #e0a800;
                }

                .form-group.full-width {
                    grid-column: span 2;
                }

                .form-group label {
                    margin-bottom: 8px;
                    font-weight: 600;
                    color: #333;
                    font-size: 14px;
                }

                .required {
                    color: #dc3545;
                }

                .form-group input,
                .form-group select,
                .form-group textarea {
                    padding: 12px 15px;
                    border: 2px solid #e9ecef;
                    border-radius: 6px;
                    font-size: 14px;
                    transition: all 0.3s;
                    background-color: white;
                }

                .form-group input:focus,
                .form-group select:focus,
                .form-group textarea:focus {
                    outline: none;
                    border-color: #007bff;
                    box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.1);
                    background-color: #f8f9ff;
                }

                .form-group textarea {
                    resize: vertical;
                    min-height: 100px;
                }

                .form-actions {
                    display: flex;
                    justify-content: center;
                    gap: 20px;
                    margin-top: 40px;
                    padding-top: 30px;
                    border-top: 1px solid #e9ecef;
                }



                .booking-item {
                    background: white;
                    border: 1px solid #e9ecef;
                    border-radius: 10px;
                    padding: 25px;
                    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
                    transition: all 0.3s;
                }

                .booking-item:hover {
                    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
                    transform: translateY(-2px);
                }

                .booking-content {
                    display: grid;
                    grid-template-columns: auto 1fr auto;
                    gap: 20px;
                    align-items: start;
                }

                .btn-update {
                    background: linear-gradient(135deg, #f35252 0%, #FF4444 100%);
                    color: white;
                    padding: 15px 40px;
                    border: none;
                    border-radius: 6px;
                    cursor: pointer;
                    font-size: 16px;
                    font-weight: 600;
                    text-transform: uppercase;
                    transition: all 0.3s;
                }

                .btn-update:hover {
                    transform: translateY(-2px);
                    box-shadow: 0 6px 20px rgba(0, 123, 255, 0.3);
                }

                .forgot-password {
                    margin-top: 20px;
                    display: flex;
                    justify-content: space-between;
                }

                .forgot-password a {
                    color: #007bff;
                    text-decoration: none;
                    font-size: 14px;
                    font-weight: 500;
                }

                .forgot-password button {
                    color: #007bff;
                    border: none;
                    background-color: inherit;
                    text-decoration: none;
                    font-size: 14px;
                    font-weight: 500;
                }

                .forgot-password a:hover {
                    text-decoration: underline;
                }

                .booking-info {
                    line-height: 35px;
                    margin-bottom: 15px;
                }

                .info-icon {
                    font-size: 15px;
                    padding: 5px
                }

                .status-badge {
                    padding: 6px 12px;
                    border-radius: 20px;
                    font-size: 12px;
                    font-weight: 600;
                }

                .status-completed {
                    background: rgb(220 252 231 / 1);
                    color: #155724;
                }

                .status-upcoming {
                    background: #cce7ff;
                    color: #004085;
                }

                .status-failed {
                    background: rgb(255 103 103);
                    color: #1c0707;
                }

                .status-pending {
                    background: #ffc107;
                    color: #721c24;
                }

                .booking-price {
                    font-size: 18px;
                    font-weight: bold;
                    color: #333;
                    margin: 5px 0;
                }

                .booking-code {
                    color: #666;
                    font-size: 15px;
                    line-height: 1.25rem;
                }


                .tab-content {
                    padding: 40px;
                }

                .history-header {
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                    margin-bottom: 30px;
                }

                .history-title {
                    font-size: 24px;
                    font-weight: bold;
                    color: #333;
                }

                .history-filters {
                    display: flex;
                    gap: 15px;
                    align-items: center;
                }

                .filter-select,
                .filter-date {
                    padding: 8px 12px;
                    border: 1px solid #ddd;
                    border-radius: 6px;
                    font-size: 14px;
                    background: white;
                }

                .booking-list {
                    display: flex;
                    flex-direction: column;
                    gap: 20px;
                }

                .tab-content.active {
                    display: block;
                }

                .payment-method {
                    font-size: 15px;
                    color: #666;
                    display: flex;
                    align-items: center;
                    gap: 5px;
                }

                .booking-summary {
                    text-align: right;
                    display: flex;
                    flex-direction: column;
                    gap: 10px;
                    align-items: flex-end;
                }

                .booking-info {
                    .ticket-detail-container {
                        padding: 40px;
                    }

                    .ticket-header {
                        text-align: center;
                        margin-bottom: 40px;
                        padding-bottom: 30px;
                        border-bottom: 2px solid #e9ecef;
                    }

                    .ticket-title {
                        font-size: 28px;
                        font-weight: bold;
                        color: #333;
                        margin-bottom: 10px;
                    }

                    .ticket-subtitle {
                        font-size: 16px;
                        color: #666;
                    }

                    .ticket-content {
                        display: grid;
                        grid-template-columns: 1fr 1fr;
                        gap: 40px;
                        margin-bottom: 40px;
                    }

                    .ticket-section {
                        background: #f8f9fa;
                        padding: 30px;
                        border-radius: 10px;
                        border: 1px solid #e9ecef;
                    }

                    .section-title {
                        font-size: 18px;
                        font-weight: bold;
                        color: #333;
                        margin-bottom: 20px;
                        display: flex;
                        align-items: center;
                        gap: 10px;
                    }

                    .movie-info {
                        display: flex;
                        gap: 20px;
                        margin-bottom: 20px;
                    }

                    .movie-poster-large {
                        width: 120px;
                        height: 180px;
                        border-radius: 10px;
                        object-fit: cover;
                        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
                    }

                    .movie-details {
                        flex: 1;
                    }

                    .movie-title-large {
                        font-size: 24px;
                        font-weight: bold;
                        color: #333;
                        margin-bottom: 10px;
                    }

                    .movie-meta {
                        display: flex;
                        flex-direction: column;
                        gap: 8px;
                        font-size: 14px;
                        color: #666;
                    }

                    .meta-item {
                        display: flex;
                        gap: 8px;
                    }

                    .detail-grid {
                        display: grid;
                        grid-template-columns: 1fr 1fr;
                        gap: 15px;
                    }

                    .detail-item {
                        display: flex;
                        justify-content: space-between;
                        align-items: center;
                        padding: 12px 0;
                        border-bottom: 1px solid #dee2e6;
                    }

                    .detail-label {
                        font-weight: 600;
                        color: #333;
                        font-size: 14px;
                    }

                    .detail-value {
                        color: #666;
                        font-size: 14px;
                        text-align: right;
                    }

                    .detail-value.highlight {
                        color: #007bff;
                        font-weight: 600;
                    }

                    .detail-value.price {
                        color: #28a745;
                        font-weight: bold;
                        font-size: 16px;
                    }

                    .qr-section {
                        text-align: center;
                        background: white;
                        padding: 30px;
                        border-radius: 10px;
                        border: 2px solid #007bff;
                        margin: 30px 0;
                    }

                    .qr-code {
                        width: 200px;
                        height: 200px;
                        background: #f8f9fa;
                        border: 2px solid #dee2e6;
                        border-radius: 10px;
                        margin: 0 auto 20px;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        font-size: 80px;
                        color: #666;
                    }

                    .qr-instructions {
                        font-size: 14px;
                        color: #666;
                        line-height: 1.5;
                    }

                    .action-buttons {
                        display: flex;
                        justify-content: center;
                        gap: 20px;
                        margin-top: 40px;
                        padding-top: 30px;
                        border-top: 2px solid #e9ecef;
                    }

                    .action-btn {
                        padding: 12px 30px;
                        border: none;
                        border-radius: 6px;
                        font-size: 14px;
                        font-weight: 600;
                        cursor: pointer;
                        transition: all 0.3s;
                        display: flex;
                        align-items: center;
                        gap: 8px;
                        text-transform: uppercase;
                    }

                    .btn-rate {
                        background: linear-gradient(135deg, #ffc107 0%, #e0a800 100%);
                        color: #212529;
                    }

                    .btn-rate:hover {
                        transform: translateY(-2px);
                        box-shadow: 0 4px 15px rgba(255, 193, 7, 0.3);
                    }

                    .additional-info {
                        background: #fff3cd;
                        border: 1px solid #ffeaa7;
                        border-radius: 10px;
                        padding: 20px;
                        margin-top: 30px;
                    }

                    .additional-info h4 {
                        color: #856404;
                        margin-bottom: 10px;
                        font-size: 16px;
                    }

                    .additional-info ul {
                        color: #856404;
                        font-size: 14px;
                        line-height: 1.6;
                        margin-left: 20px;
                    }
                }

                @media (max-width: 968px) {
                    .profile-container {
                        grid-template-columns: 1fr;
                    }

                    .form-grid {
                        grid-template-columns: 1fr;
                    }

                    .form-group.full-width {
                        grid-column: span 1;
                    }

                    .info-row {
                        display: flex;
                        align-items: center;
                        gap: 8px;
                        font-size: 14px;
                        color: #666;
                    }

                    .tab-item {
                        padding: 12px 15px;
                        font-size: 12px;
                    }
                }
            }
        </style>

        <!-- Main Content -->
        @use('chillerlan\QRCode\QRCode')
        <div class="fix"></div>
        @if (session()->has('success'))
        <div class="alertSuccess">
            {{ session('success') }}
        </div>
        @endif
        <div class="main-content">
            <div class="profile-container">
                <!-- Profile Sidebar -->
                <div wire:poll.2s class="profile-sidebar">
                    <div class="profile-avatar">
                        <div class="avatar-circle">
                            @if ($avatar != null)
                            <img src="{{ $avatar->temporaryUrl() }}">
                            @elseif (!empty($user->avatar))
                            <img src="{{ asset('storage/' . ($user->avatar ?? '404.webp')) }}">
                            @endif
                        </div>
                        <div class="profile-name">{{$user->name}}</div>
                        <div class="profile-join-date">Tham gia: {{$user->created_at->format('d/m/Y')}}</div>
                    </div>
                    <div class="profile-info">
                        <div class="info-item">
                            <span class="info-label">Họ tên:</span>
                            <span class="info-value"> {{$user->name}}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Email:</span>
                            <span class="info-value">{{$user->email}}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Số điện thoại:</span>
                            <span class="info-value">{{$user->phone}}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Ngày sinh:</span>
                            <span class="info-value">{{$user->birthday != null ? $user->birthday->format('d/m/Y') :
                                'N/A'}}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Giới tính:</span>
                            <span class="info-value">
                                @switch($user->gender)
                                @case('man')
                                Nam
                                @break;
                                @case('woman')
                                Nữ
                                @break
                                @case('other')
                                Khác
                                @break
                                @default
                                N\A
                                @endswitch
                            </span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Địa chỉ:</span>
                            <span class="info-value">{{$user->address}}</a></span>
                        </div>
                    </div>
                </div>

                <!-- Profile Main Content -->
                <div class="profile-main">
                    <div class="tab-header">
                        <button class="tab-item @if($tabCurrent === 'info') active  @endif"
                            wire:click="$set('tabCurrent', 'info')">
                            Thông tin tài khoản
                        </button>
                        <button class="tab-item @if($tabCurrent === 'booking') active  @endif"
                            wire:click="$set('tabCurrent', 'booking')">
                            Lịch sử xem phim
                        </button>
                    </div>
                    @if ($tabCurrent === 'info')
                    <div class="form-container">
                        <form wire:submit.prevent="update" enctype="multipart/form-data" class="was-validated">
                            <div class="upload-section">
                                <div class="upload-buttons">
                                    <input type="file" id="fileUpload" style="display:none" wire:model.live='avatar' />
                                    <label for="fileUpload" class="btn btn-upload"><i
                                            class="fa-solid fa-arrow-up-from-bracket"></i> Tải ảnh lên</label>
                                </div>
                                <p style="color: #666; font-size: 12px;">Định dạng: JPG, PNG • Kích thước tối đa: 20MB
                                </p>
                                @error('avatar')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-grid">
                                <div class="form-group">
                                    <label for="fullname"><span class="required">*</span> Họ tên</label>
                                    <input type="text" id="fullname" name="fullname" wire:model.live='name'
                                        placeholder="Nhập họ tên">
                                    @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="phone"><span class="required">*</span> Số điện thoại</label>
                                    <input type="number" id="phone" name="phone" wire:model.live='phone'
                                        placeholder="Nhập số điện thoại">
                                    @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="birthday"><span class="required">*</span> Ngày sinh</label>
                                    <input type="date" id="birthday" name="birthday" wire:model.live='birthday'>
                                    @error('birthday')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="gender">Giới tính</label>
                                    <select id="gender" name="gender">
                                        <option value="">Chọn giới tính</option>
                                        <option value="man" {{$user->gender == 'man'?'selected':''}} >Nam</option>
                                        <option value="woman" {{$user->gender == 'woman'?'selected':''}}>Nữ</option>
                                        <option value="other" {{$user->gender == 'other'?'selected':''}}>Khác</option>
                                    </select>
                                    @error('gender')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group full-width">
                                    <label for="address">Địa chỉ</label>
                                    <textarea id="address" name="address" placeholder="Nhập địa chỉ chi tiết"
                                        wire:model.live='address'></textarea>
                                    @error('address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="forgot-password">
                                <button type="button" wire:click='openModal'>🔒 Đổi mật khẩu</button>
                                <a href="{{route('userConfirm')}}">
                                    Xóa tài khoản
                                </a>
                            </div>

                            <div class="form-actions">
                                <button type="submit" class="btn-update">📝 Cập nhật</button>
                            </div>
                        </form>
                    </div>
                    @endif
                    @if ($tabCurrent === 'booking')
                    <div wire:poll.2s class="tab-content">
                        <div class="history-header">
                            <h2 class="history-title">Lịch sử đơn hàng</h2>
                            <div class="history-filters">
                                <input type="text" class="filter-date" wire:model.live='nameFilter'
                                    placeholder="Tìm kiếm theo tên phim...">
                                <select class="filter-select" wire:model.live='statusFilter'>
                                    <option value="">Tất cả trạng thái</option>
                                    <option value="failed">Đã thất bại</option>
                                    <option value="paid">Đã thanh toán</option>
                                    <option value="pending">Chờ thanh toán</option>
                                    <option value="expired">Hết hạn</option>
                                </select>
                                <input type="date" class="filter-date" id="dateFilter" wire:model.live='dateFilter'>
                            </div>
                        </div>
                        <div class="booking-list">
                            @forelse ($bookings as $booking)

                            <div class="booking-item">
                                <div class="booking-content">
                                    <img src="{{ asset('storage/' . ($booking->showtime->movie->poster ?? '404.webp')) }}"
                                        class="movie-poster">

                                    <div class="booking-details">
                                        <h3 class="movie-title">{{$booking->showtime->movie->title}}</h3>
                                        <div class="booking-info">
                                            <div class="info-row">
                                                <span class="info-icon"><i
                                                        class="fa-regular fa-location-dot"></i></span>
                                                <span>Se7en Cinema Landmark 81</span>
                                            </div>
                                            <div class="info-row">
                                                <span class="info-icon"><i class="fa-light fa-calendar"></i></span>
                                                <span>{{$booking->showtime->start_time->format('d/m/Y')}}</span>
                                            </div>
                                            <div class="info-row">
                                                <span class="info-icon"><i class="fa-light fa-clock"></i></span>
                                                <span>{{$booking->showtime->start_time->format('H:i')}}</span>
                                            </div>
                                            <div class="info-row">
                                                <span class="info-icon"><i class="fa-light fa-seat-airline"></i></span>
                                                <span>Ghế:
                                                    @forelse ($booking->seats as $seat)
                                                    {{$seat->seat_row}}{{sprintf('%02d',
                                                    $seat->seat_number);}}@if(!$loop->last),@endif
                                                    @empty
                                                    Không có ghế
                                                    @endforelse
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="booking-summary">
                                        <div
                                            class="status-badge @if($booking->status === 'paid') status-completed @elseif($booking->status === 'pending') status-pending @elseif($booking->status == 'expired') status-upcoming @elseif($booking->status == 'failed') status-failed @endif">
                                            @switch($booking->status)
                                            @case('paid')
                                            Đã thanh toán
                                            @break
                                            @case('expired')
                                            Đã hết hạn
                                            @break
                                            @case('pending')
                                            Chờ thanh toán
                                            @break
                                            @case('failed')
                                            Đã thất bại
                                            @break
                                            @default

                                            @endswitch
                                        </div>
                                        <div class="booking-price">
                                            {{number_format($booking->total_price,0,',','.')}}
                                            VNĐ
                                        </div>
                                        <div class="booking-code">Mã: {{$booking->booking_code}}</div>
                                        <div class="payment-method">
                                            <span><i class="fa-light fa-credit-card"></i></span>
                                            @switch($booking->payment_method)
                                            @case('cash')
                                            <span>Tiền mặt</span>
                                            @break
                                            @case('e_wallet')
                                            <span>Ví điện tử</span>
                                            @break
                                            @case('bank_transfer')
                                            <span>Chuyển khoản ngân hàng</span>
                                            @break
                                            @case('credit_card')
                                            <span>Thẻ tín dụng</span>
                                            @break
                                            @break
                                            @default
                                            <span>N/A</span>
                                            @endswitch
                                        </div>
                                        <div class="booking-actions">
                                            <button class="action-btn btn-rate">
                                                <i class="fa-light fa-star"></i> Đánh giá
                                            </button>
                                            <button class="action-btn btn-detail"
                                                wire:click="detailBooking({{$booking->id}})">
                                                <i class="fa-light fa-eye"></i> Chi tiết
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @empty
                            <div style="text-align: center">
                                <p>Không có lịch sử mua vé</p>
                            </div>
                            @endforelse
                            <div>
                                {{ $bookings->links('pagination.user-info') }}
                            </div>
                        </div>
                    </div>
                    @endif
                    @if ($tabCurrent === 'booking-info')
                    <div class="booking-info">
                        <div class="ticket-detail-container">
                            <div class="ticket-header">
                                <h1 class="ticket-title">Chi Tiết Đơn Hàng</h1>
                                <p class="ticket-subtitle">Thông tin đầy đủ về vé đã đặt</p>
                            </div>

                            <div class="ticket-content">
                                <div class="ticket-section">
                                    <h3 class="section-title">
                                        🎬 Thông Tin Phim
                                    </h3>
                                    <div class="movie-info">
                                        <img src="{{ asset('storage/' . ($bookingInfo->showtime->movie->poster ?? '404.webp')) }}"
                                            class="movie-poster-large">
                                        <div class="movie-details">
                                            <h2 class="movie-title-large" id="movieTitle">
                                                {{$bookingInfo->showtime->movie->title}}</h2>
                                            <div class="movie-meta">
                                                <div class="meta-item">
                                                    <span>🎭</span>
                                                    <span>Thể loại:
                                                        @forelse ($bookingInfo->showtime->movie->genres as $item)
                                                        {{$item->name}} @if (!$loop->last),@endif
                                                        @empty
                                                        Không có
                                                        @endforelse
                                                    </span>
                                                </div>
                                                <div class="meta-item">
                                                    <span>⏱️</span>
                                                    <span>Thời lượng: {{$bookingInfo->showtime->movie->duration}}
                                                        phút</span>
                                                </div>
                                                <div class="meta-item">
                                                    <span>🔞</span>
                                                    <span>Độ tuổi:
                                                        {{$bookingInfo->showtime->movie->age_restriction}}</span>
                                                </div>
                                                <div class="meta-item">
                                                    <span>🔍</span>
                                                    <span>Định dạng: {{$bookingInfo->showtime->movie->format}}</span>
                                                </div>
                                                <div class="meta-item">
                                                    <span>🌟</span>
                                                    <span>Đánh giá:
                                                        {{$bookingInfo->showtime->movie->ratings->avg('score')}}/10</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Booking Information -->
                                <div class="ticket-section">
                                    <h3 class="section-title">
                                        📋 Thông Tin Đặt Vé
                                    </h3>
                                    <div class="detail-grid">
                                        <div class="detail-item">
                                            <span class="detail-label">Mã vé:</span>
                                            <span class="detail-value highlight"
                                                id="bookingCode">{{$bookingInfo->booking_code}}</span>
                                        </div>
                                        <div class="detail-item">
                                            <span class="detail-label">Ngày đặt:</span>
                                            <span class="detail-value"
                                                id="bookingDate">{{$bookingInfo->created_at->format('d/m/Y')}}</span>
                                        </div>
                                        <div class="detail-item">
                                            <span class="detail-label">Rạp chiếu:</span>
                                            <span class="detail-value" id="cinema">SE7ENCinema Landmark 81</span>
                                        </div>
                                        <div class="detail-item">
                                            <span class="detail-label">Phòng chiếu:</span>
                                            <span class="detail-value"
                                                id="room">{{$bookingInfo->showtime->room->name}}</span>
                                        </div>
                                        <div class="detail-item">
                                            <span class="detail-label">Ngày chiếu:</span>
                                            <span class="detail-value"
                                                id="showDate">{{$bookingInfo->showtime->start_time->format('d/m/Y')}}</span>
                                        </div>
                                        <div class="detail-item">
                                            <span class="detail-label">Giờ chiếu:</span>
                                            <span class="detail-value"
                                                id="showTime">{{$bookingInfo->showtime->start_time->format('H:i')}} -
                                                {{$bookingInfo->showtime->end_time->format('H:i')}}</span>
                                        </div>
                                        <div class="detail-item">
                                            <span class="detail-label">Ghế ngồi:</span>
                                            <span class="detail-value highlight">
                                                @forelse ($bookingInfo->seats as $seat)
                                                {{$seat->seat_row}}{{$seat->seat_number}}@if(!$loop->last),@endif
                                                @empty
                                                N\A
                                                @endforelse
                                            </span>
                                        </div>
                                        <div class="detail-item">
                                            <span class="detail-label">Số lượng vé:</span>
                                            <span class="detail-value" id="ticketCount">{{
                                                number_format($bookingInfo->seats->count(), 0, '.', '.') }} vé</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Payment Information -->
                            <div class="ticket-section">
                                <h3 class="section-title">
                                    💳 Thông Tin Thanh Toán
                                </h3>
                                <div class="detail-grid">
                                    <div class="detail-item" style="grid-column:span 2;">
                                        <span class="detail-label">Thức ăn kèm:
                                            <div style="text-wrap: balance;">
                                                @if ($bookingInfo->foodOrderItems->isNotEmpty())
                                                @forelse ($bookingInfo->foodOrderItems as $foodOrder)
                                                <div class="food-item">
                                                    <div class="food-info">
                                                        <div class="food-name-row">
                                                            <span
                                                                class="food-name">{{$foodOrder->variant->foodItem->name}}</span>
                                                            <span
                                                                class="quantity-badge">({{$foodOrder->quantity}}x)</span>
                                                        </div>
                                                        <div class="variants">
                                                            @foreach($foodOrder->variant->attributeValues as $attributeValue)
                                                                {{ $attributeValue->attribute->name }} :{{ $attributeValue->value }}@if(!$loop->last),@endif
                                                            @endforeach

                                                        </div>
                                                        <div class="price-detail">{{ number_format($foodOrder->price, 0,
                                                            ',', '.') }} × {{$foodOrder->quantity}}
                                                        </div>
                                                    </div>
                                                    <span class="detail-value">
                                                        <div class="food-total">
                                                            {{
                                                            number_format(
                                                            $foodOrder->quantity*$foodOrder->price,0, '.', '.')
                                                            }}
                                                        </div>
                                                    </span>
                                                </div>
                                                @empty
                                                @endforelse
                                                @endif
                                            </div>
                                        </span>
                                        <span class="detail-value">
                                            @if (!$bookingInfo->foodOrderItems->isNotEmpty())
                                            Không
                                            @endif
                                        </span>

                                    </div>
                                    <div class="detail-item">
                                        <span class="detail-label">Giá vé
                                            ({{number_format($bookingInfo->seats->count(),0, '.', '.') }}x):</span>
                                        <span class="detail-value">
                                            {{number_format($bookingInfo->showtime->movie->price *
                                            $bookingInfo->seats->count(), 0, '.','.')}}
                                        </span>
                                    </div>

                                    <div class="detail-item">
                                        <span class="detail-label">Tổng tiền:</span>
                                        <span class="detail-value price"
                                            id="totalAmount">{{number_format($bookingInfo->total_price, 0, '.',
                                            '.')}}</span>
                                    </div>
                                    <div class="detail-item">
                                        <span class="detail-label">Phương thức:</span>
                                        <span class="detail-value" id="paymentMethod">
                                            @switch($bookingInfo->payment_method)
                                            @case('bank_transfer')
                                            <span>Chuyển khoản ngân hàng</span>
                                            @break
                                            @case('e_wallet')
                                            <span>Ví điện tử</span>
                                            @break
                                            @case('credit_card')
                                            <span>Thẻ tín dụng</span>
                                            @break
                                            @case('cash')
                                            <span>Tiền mặt</span>
                                            @break
                                            @default
                                            <span>N/A</span>
                                            @endswitch
                                        </span>
                                    </div>
                                    <div class="detail-item">
                                        <span class="detail-label">Trạng thái:</span>
                                        <span class="detail-value highlight">
                                            @switch($bookingInfo->status)
                                            @case('paid')
                                            Đã thanh toán
                                            @break
                                            @case('expired')
                                            Đã hết hạn
                                            @break
                                            @case('pending')
                                            Chờ thanh toán
                                            @break
                                            @case('failed')
                                            Đã thất bại
                                            @break
                                            @default
                                            N/A
                                            @endswitch
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <!-- QR Code Section -->
                            <div class="qr-section">
                                <h3 class="section-title">
                                    📱 Mã QR
                                </h3>
                                <div class="qr-code">
                                    <img src="{{ (new QRCode)->render($bookingInfo->booking_code) }}" alt="QR code"
                                        style="width: 100%; height: 100%; border-radius: 0;">
                                </div>
                                <div class="qr-instructions">
                                    <strong>Hướng dẫn sử dụng:</strong><br>
                                    • Xuất trình mã QR này tại quầy vé hoặc cổng vào rạp<br>
                                    • Đảm bảo màn hình sáng và rõ nét<br>
                                    • Có mặt trước giờ chiếu 15 phút để làm thủ tục
                                </div>
                            </div>

                            <!-- Additional Information -->
                            <div class="additional-info">
                                <h4>📝 Lưu Ý Quan Trọng</h4>
                                <ul>
                                    <li>Vé đã mua không thể đổi trả sau khi thanh toán</li>
                                    <li>Vui lòng có mặt trước giờ chiếu 15 phút</li>
                                    <li>Không được mang thức ăn, đồ uống từ bên ngoài vào rạp</li>
                                    <li>Tắt điện thoại hoặc chuyển sang chế độ im lặng trong suốt buổi chiếu</li>
                                    <li>Liên hệ hotline 1900-6017 nếu cần hỗ trợ</li>
                                </ul>
                            </div>

                            <!-- Action Buttons -->
                            <div class="action-buttons" id="actionButtons">
                                <button class="action-btn btn-rate">
                                    <i class="fa-light fa-star"></i> Đánh Giá Phim
                                </button>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @if ($modal)
    <div class="popup-overlay">
        <div class="popup-container">
            <div class="popup-header">
                <h2 class="popup-title">
                    Đổi Mật Khẩu
                </h2>
                <p class="popup-subtitle">Cập nhật mật khẩu để bảo mật tài khoản</p>
                <button type="button" class="close-btn" wire:click='closeModal'>×</button>
            </div>
            <div class="popup-body">
                <form wire:submit.prevent='changePassword'>
                    <div class="form-group">
                        <label class="form-label">
                            Mật khẩu hiện tại <span class="required">*</span>
                        </label>
                        <div class="input-wrapper">
                            <input type="password" class="form-input" wire:model.live='currentPassword'
                                placeholder="Nhập mật khẩu hiện tại">
                            <button type="button" class="password-toggle" onclick="
                                const input = this.previousElementSibling;
                                input.type = input.type === 'password' ? 'text' : 'password';">
                                <i class="fa-light fa-eye"></i>
                            </button>
                        </div>
                        @if (session()->has('error'))
                        <div class="error-message">
                            ⚠️ <span> {{session('error')}}</span>
                        </div>
                        @endif
                    </div>

                    <!-- New Password -->
                    <div class="form-group">
                        <label class="form-label">
                            Mật khẩu mới <span class="required">*</span>
                        </label>
                        <div class="input-wrapper">
                            <input type="password" class="form-input" placeholder="Nhập mật khẩu mới"
                                wire:model.live='newPassword'>
                            <button type="button" class="password-toggle"
                                onclick="this.previousElementSibling.type = this.previousElementSibling.type === 'password' ? 'text' : 'password'">
                                <i class="fa-light fa-eye"></i>
                            </button>
                        </div>
                        @error('newPassword')
                        <div class="error-message">
                            ⚠️ <span>{{$message}}</span>
                        </div>
                        @enderror
                    </div>

                    <!-- Confirm Password -->
                    <div class="form-group">
                        <label class="form-label">
                            Xác nhận mật khẩu mới <span class="required">*</span>
                        </label>
                        <div class="input-wrapper">
                            <input type="password" class="form-input" wire:model.live='confirmPassword'
                                placeholder="Nhập lại mật khẩu mới">
                            <button type="button" class="password-toggle"
                                onclick="this.previousElementSibling.type = this.previousElementSibling.type === 'password' ? 'text' : 'password'">
                                <i class="fa-light fa-eye"></i>
                            </button>
                        </div>
                        @error('confirmPassword')
                        <div class="error-message">
                            ⚠️ <span>{{$message}}</span>
                        </div>
                        @enderror
                    </div>
                    <!-- Popup Footer -->
                    <div class="popup-footer">
                        <button type="button" class="btn btn-cancel" wire:click='closeModal'>
                            Hủy bỏ
                        </button>
                        <button type="submit" class="btn btn-changePass">
                            Đổi mật khẩu
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif
</div>