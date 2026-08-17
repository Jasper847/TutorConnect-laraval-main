@extends('admin.layout')

@section('title', 'Reviews')

@section('content')
<style>
    /* ===== PAGE STYLES ===== */
    .section-card {
        background: white;
        border-radius: 20px;
        padding: 25px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.05);
    }
    
    .section-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: #1a1a2e;
        margin: 0 0 20px;
    }
    
    table {
        width: 100%;
        border-collapse: collapse;
    }
    
    th {
        text-align: left;
        padding: 12px 15px;
        color: #888;
        font-weight: 600;
        font-size: 0.85rem;
        border-bottom: 2px solid #f0f4f8;
    }
    
    td {
        padding: 12px 15px;
        border-bottom: 1px solid #f0f4f8;
        color: #333;
    }
    
    .btn-delete {
        background: #dc3545;
        color: white;
        border: none;
        padding: 5px 15px;
        border-radius: 8px;
        cursor: pointer;
        font-size: 0.8rem;
        transition: all 0.3s;
    }
    
    .btn-delete:hover {
        background: #c82333;
        transform: translateY(-2px);
    }
    
    /* ===== CUSTOM DELETE MODAL ===== */
    .modal-overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        z-index: 9999;
        justify-content: center;
        align-items: center;
        animation: fadeIn 0.3s ease;
    }
    
    .modal-overlay.show {
        display: flex;
    }
    
    @keyframes fadeIn {
        0% { opacity: 0; }
        100% { opacity: 1; }
    }
    
    .modal-box {
        background: white;
        border-radius: 25px;
        padding: 35px 40px;
        max-width: 420px;
        width: 90%;
        text-align: center;
        box-shadow: 0 25px 60px rgba(0, 0, 0, 0.3);
        animation: modalPop 0.3s ease;
    }
    
    @keyframes modalPop {
        0% { transform: scale(0.8); opacity: 0; }
        100% { transform: scale(1); opacity: 1; }
    }
    
    .modal-icon {
        font-size: 3.5rem;
        margin-bottom: 10px;
    }
    
    .modal-title {
        font-size: 1.4rem;
        font-weight: 700;
        color: #1a1a2e;
        margin: 0 0 8px;
    }
    
    .modal-message {
        color: #666;
        font-size: 0.95rem;
        margin: 0 0 5px;
        line-height: 1.6;
    }
    
    .modal-warning {
        color: #999;
        font-size: 0.85rem;
        margin: 0 0 25px;
    }
    
    .modal-buttons {
        display: flex;
        gap: 12px;
        justify-content: center;
    }
    
    .btn-cancel {
        padding: 10px 35px;
        border: 2px solid #e0e0e0;
        border-radius: 12px;
        background: white;
        color: #555;
        font-size: 0.95rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
    }
    
    .btn-cancel:hover {
        background: #f5f5f5;
        border-color: #ccc;
    }
    
    .btn-confirm-delete {
        padding: 10px 35px;
        border: none;
        border-radius: 12px;
        background: #dc3545;
        color: white;
        font-size: 0.95rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
    }
    
    .btn-confirm-delete:hover {
        background: #c82333;
        transform: translateY(-2px);
        box-shadow: 0 5px 20px rgba(220, 53, 69, 0.3);
    }
    
    /* ===== TOAST NOTIFICATION ===== */
    .toast {
        position: fixed;
        bottom: 30px;
        right: 30px;
        background: #28a745;
        color: white;
        padding: 16px 30px;
        border-radius: 12px;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
        z-index: 99999;
        font-weight: 600;
        font-size: 0.95rem;
        display: none;
        align-items: center;
        gap: 12px;
        animation: slideUp 0.4s ease;
    }
    
    .toast.show {
        display: flex;
    }
    
    @keyframes slideUp {
        0% { transform: translateY(100px); opacity: 0; }
        100% { transform: translateY(0); opacity: 1; }
    }
    
    .toast-close {
        background: transparent;
        border: none;
        color: white;
        font-size: 1.2rem;
        cursor: pointer;
        padding: 0 5px;
        opacity: 0.7;
    }
    
    .toast-close:hover {
        opacity: 1;
    }
</style>

<div class="section-card">
    <h3 class="section-title">⭐ All Reviews</h3>
    
    <!-- ===== TOAST SESSION CHECK ===== -->
    @if(session('toast'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                showToast('✅ {{ session('toast') }}');
            });
        </script>
    @endif
    
    @if(session('error'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                showToast('❌ {{ session('error') }}', 'error');
            });
        </script>
    @endif
    
    <!-- ===== TABLE ===== -->
    <table>
        <thead>
            <tr>
                <th>Student</th>
                <th>Tutor</th>
                <th>Rating</th>
                <th>Review</th>
                <th>Date</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($reviews as $review)
            <tr>
                <td>{{ $review->student->name ?? 'N/A' }}</td>
                <td>{{ $review->tutor->name ?? 'N/A' }}</td>
                <td>{{ $review->rating }} ⭐</td>
                <td>{{ Str::limit($review->comment, 50) }}</td>
                <td>{{ $review->created_at->format('M d, Y') }}</td>
                <td>
                    <!-- ⭐ DELETE BUTTON -->
                    <button class="btn-delete" onclick="openDeleteModal({{ $review->id }})">
                        🗑️ Delete
                    </button>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- ============================================================ -->
<!-- ===== CUSTOM DELETE MODAL ===== -->
<!-- ============================================================ -->
<div class="modal-overlay" id="deleteModal">
    <div class="modal-box">
        <div class="modal-icon">⚠️</div>
        <h3 class="modal-title">Delete Review?</h3>
        <p class="modal-message">Are you sure you want to delete this review?</p>
        <p class="modal-warning">This action cannot be undone!</p>
        <div class="modal-buttons">
            <button class="btn-cancel" onclick="closeDeleteModal()">Cancel</button>
            <button class="btn-confirm-delete" id="confirmDeleteBtn">Yes, Delete</button>
        </div>
    </div>
</div>

<!-- ============================================================ -->
<!-- ===== HIDDEN DELETE FORM (DELETE METHOD KE SAATH) ===== -->
<!-- ============================================================ -->
<form id="deleteForm" action="" method="POST" style="display:none;">
    @csrf
    @method('DELETE')
</form>

<!-- ============================================================ -->
<!-- ===== TOAST NOTIFICATION ===== -->
<!-- ============================================================ -->
<div class="toast" id="toast">
    <span id="toastMessage">✅ Review deleted successfully!</span>
    <button class="toast-close" onclick="hideToast()">✕</button>
</div>

<!-- ============================================================ -->
<!-- ===== JAVASCRIPT ===== -->
<!-- ============================================================ -->
<script>
    let deleteId = null;
    
    // ===== OPEN DELETE MODAL =====
    function openDeleteModal(id) {
        deleteId = id;
        document.getElementById('deleteModal').classList.add('show');
        document.getElementById('deleteModal').style.display = 'flex';
    }
    
    // ===== CLOSE DELETE MODAL =====
    function closeDeleteModal() {
        document.getElementById('deleteModal').classList.remove('show');
        document.getElementById('deleteModal').style.display = 'none';
        deleteId = null;
    }
    
    // ===== CONFIRM DELETE - FORM SUBMIT KARO =====
    document.getElementById('confirmDeleteBtn').addEventListener('click', function() {
        if (deleteId) {
            const form = document.getElementById('deleteForm');
            form.action = '/admin/review/delete/' + deleteId;
            form.submit();
        }
    });
    
    // ===== CLOSE MODAL ON OUTSIDE CLICK =====
    document.getElementById('deleteModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeDeleteModal();
        }
    });
    
    // ===== TOAST FUNCTIONS =====
    function showToast(message, type = 'success') {
        const toast = document.getElementById('toast');
        const toastMessage = document.getElementById('toastMessage');
        
        toastMessage.textContent = message;
        toast.className = 'toast';
        if (type === 'error') {
            toast.style.background = '#dc3545';
        } else {
            toast.style.background = '#28a745';
        }
        toast.classList.add('show');
        toast.style.display = 'flex';
        
        setTimeout(function() {
            hideToast();
        }, 4000);
    }
    
    function hideToast() {
        const toast = document.getElementById('toast');
        toast.classList.remove('show');
        toast.style.display = 'none';
    }
</script>
@endsection