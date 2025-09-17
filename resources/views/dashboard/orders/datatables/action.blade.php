<div class="form-group">
    <div class="btn-group" role="group" aria-label="Button group with nested dropdown">

        <a href="{{ route('dashboard.orders.show', $row->id) }}" class="btn btn-outline-primary ">
            {{ __('dashboard.show') }} <i class="la la-eye"></i>
        </a>


        <button id="btnGroupDrop2" order-id="{{ $row->id }}" type="button"
            class="delete_confirm_btn btn btn-outline-danger">
            {{ __('dashboard.delete') }}<i class="la la-trash"></i>
        </button>


    </div>
</div>
