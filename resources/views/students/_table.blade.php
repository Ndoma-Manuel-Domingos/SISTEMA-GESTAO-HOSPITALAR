<table class="table table-striped">
    <thead>
        <tr>
            <th>#</th>
            <th>Nome</th>
            <th>Email</th>
            <th>Curso</th>
            <th>Inscrição</th>
            <th class="text-end">Ações</th>
        </tr>
    </thead>
    <tbody>
        @forelse($students as $student)
        <tr>
            <td>{{ $student->id }}</td>
            <td>{{ $student->full_name }}</td>
            <td>{{ $student->email }}</td>
            <td>{{ $student->course }}</td>
            <td>{{ optional($student->enrolled_at)->format('Y-m-d') }}</td>
            <td class="text-end">
                <button class="btn btn-sm btn-light-primary btn-edit" data-id="{{ $student->id }}">Editar</button>
                <button class="btn btn-sm btn-light-danger btn-delete" data-id="{{ $student->id }}">Eliminar</button>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="6" class="text-center">Nenhum estudante encontrado.</td>
        </tr>
        @endforelse
    </tbody>
</table>

@include('students._pagination')
