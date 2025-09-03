@extends('layouts.admin')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Newsletters') }}</div>

                <div class="card-body">
                <?php
$emails = file('newsletter.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
?>

<table class="table table-bordered" id="example2">
    <thead>
        <tr>
            <th>SR#</th>
            <th>Email</th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($emails)) : ?>
            <?php foreach ($emails as $index => $email) : ?>
                <tr>
                    <td><?php echo $index + 1; ?></td>
                    <td><?php echo htmlspecialchars($email); ?></td>
                </tr>
            <?php endforeach; ?>
        <?php else : ?>
            <tr>
                <td colspan="2">No emails found.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
