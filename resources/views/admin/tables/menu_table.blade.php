@forelse ($menus as $menu)
    <tr class="menu-row">
        <!-- Image Column -->
        <td>
            @if ($menu->image)
                <img src="{{ asset('storage/' . $menu->image) }}" alt="{{ $menu->name }}" class="img-fluid" width="50">
            @else
                <span>No Image</span>
            @endif
        </td>
        <!-- Name, Category, Description -->
        <td>{{ $menu->name }}</td>
        <td>{{ $menu->category }}</td>
        <!-- Price (Remove trailing .00 if present) -->
        <td>
            @if (floor($menu->price) == $menu->price)
                {{ number_format($menu->price, 0) }}
            @else
                {{ number_format($menu->price, 2) }}
            @endif
        </td>
        <td>{{ $menu->description }}</td>
        <!-- Action Column (View, Edit, Delete) -->
        <td>
            <a href="{{ route('admin.menu.show', $menu->id) }}" class="btn btn-sm btn-info" title="View">
                <i class="fa fa-eye"></i>
            </a>
            <a href="{{ route('admin.menu.edit', $menu->id) }}" class="btn btn-sm btn-warning" title="Edit">
                <i class="fa fa-edit"></i>
            </a>
            <form action="{{ route('admin.menu.destroy', $menu->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this menu?');">
                @csrf
                @method('DELETE')
                <button class="btn btn-sm btn-danger" type="submit" title="Delete">
                    <i class="fa fa-trash"></i>
                </button>
            </form>
        </td>
    </tr>
@empty
    <tr id="no-menus-row">
        <td colspan="6">There are no menus available.</td>
    </tr>
@endforelse