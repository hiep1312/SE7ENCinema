<div>
    <div class="modal show d-block" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5>Edit User</h5>
                    <button type="button" class="btn-close" wire:click="$dispatch('closeEditModal')"></button>
                </div>
                <div class="modal-body">
                    <input type="text" wire:model="name" class="form-control mb-2" placeholder="Name">
                    <input type="email" wire:model="email" class="form-control" placeholder="">
                    <input type="email" wire:model="email" class="form-control" placeholder="Email">
                    <input type="email" wire:model="email" class="form-control" placeholder="Email">
                    <input type="email" wire:model="email" class="form-control" placeholder="Email">
                    <input type="email" wire:model="email" class="form-control" placeholder="Email">
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" wire:click="$dispatch('closeEditModal')">Cancel</button>
                    <button class="btn btn-primary" wire:click="update">Save</button>
                </div>
            </div>
        </div>
    </div>
</div>