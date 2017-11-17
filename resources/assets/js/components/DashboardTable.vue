<template>
    <table class="table">
        <thead class="thead-dark">
        <tr>
            <th scope="col">Název</th>
            <th scope="col">Přípona</th>
            <th scope="col">Čas přidání</th>
        </tr>
        </thead>
        <tbody>
        @foreach($folders as $folder)
            <tr>
                <td><a href="#">{{ $folder->name }}</a></td>
                <td>Složka</td>
                <td>{{ $folder->created_at->diffForHumans() }}</td>
            </tr>
        @endforeach
        @foreach($documents as $document)
            <tr>
                <td>{{ $document->name }}</td>
                <td>{{ $document->suffix }}</td>
                <td>{{ $document->created_at->diffForHumans() }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</template>

<script>
    export default {
        mounted() {
            axios.get('/folder'
        }
    }
</script>
