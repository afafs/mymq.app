@extends('user.layout')

@php
    $selLang = \App\Models\User\Language::where([['code', \Illuminate\Support\Facades\Session::get('currentLangCode')], ['user_id', \Illuminate\Support\Facades\Auth::id()]])->first();
    $userDefaultLang = \App\Models\User\Language::where([['user_id', \Illuminate\Support\Facades\Auth::id()], ['is_default', 1]])->first();
    $userLanguages = \App\Models\User\Language::where('user_id', \Illuminate\Support\Facades\Auth::id())->get();
@endphp
@if (!empty($selLang) && $selLang->rtl == 1)
    @section('styles')
        <style>
            form:not(.modal-form) input,
            form:not(.modal-form) textarea,
            form:not(.modal-form) select,
            select[name='userLanguage'] {
                direction: rtl;
            }

            form:not(.modal-form) .note-editor.note-frame .note-editing-area .note-editable {
                direction: rtl;
                text-align: right;
            }
        </style>
    @endsection
@endif

@section('content')
    <div class="page-header">
        <h4 class="page-title">{{ $keywords['Blog_Categories'] ?? __('Blog Categories') }}</h4>
        <ul class="breadcrumbs">
            <li class="nav-home">
                <a href="{{ route('user-dashboard') }}">
                    <i class="flaticon-home"></i>
                </a>
            </li>
            <li class="separator">
                <i class="flaticon-right-arrow"></i>
            </li>
            <li class="nav-item">
                <a href="#">{{ $keywords['Blogs'] ?? __('Blogs') }}</a>
            </li>
            <li class="separator">
                <i class="flaticon-right-arrow"></i>
            </li>
            <li class="nav-item">
                <a href="#">{{ $keywords['Categories'] ?? __('Categories') }}</a>
            </li>
        </ul>
    </div>
    <div class="row">
        <div class="col-md-12">

            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="card-title d-inline-block">{{ $keywords['Categories'] ?? __('Categories') }}</div>
                        </div>
                        <div class="col-lg-3">

                        </div>
                        <div class="col-lg-4 offset-lg-1 mt-2 mt-lg-0">
                            @if (!is_null($userDefaultLang))
                                <a href="#" class="btn btn-primary float-right btn-sm" data-toggle="modal"
                                    data-target="#createModal"><i
                                        class="fas fa-plus"></i>{{ $keywords['Add_Blog_Category'] ?? __(' Add Blog Category') }}</a>
                                <button class="btn btn-danger float-right btn-sm mr-2 d-none bulk-delete"
                                    data-href="{{ route('user.blog.category.bulk.delete') }}"><i
                                        class="flaticon-interface-5"></i>
                                    {{ $keywords['Delete'] ?? __(' Delete') }}</button>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12">
                            @if (is_null($userDefaultLang))
                                <h3 class="text-center">{{ $keywords['NO_LANGUAGE_FOUND'] ?? __(' NO LANGUAGE FOUND') }}
                                </h3>
                            @else
                                @if (count($bcategorys) == 0)
                                    <h3 class="text-center">
                                        {{ $keywords['NO_BLOG_CATEGORY_FOUND'] ?? __('NO BLOG CATEGORY FOUND') }}</h3>
                                @else
                                    <div class="table-responsive">
                                        <table class="table table-striped mt-3" id="basic-datatables">
                                            <thead>
                                                <tr>
                                                    <th scope="col">
                                                        <input type="checkbox" class="bulk-check" data-val="all">
                                                    </th>
                                                    <th scope="col">{{ $keywords['Name'] ?? __('Name') }}</th>
                                                    <th scope="col">{{ $keywords['Status'] ?? __('Status') }}</th>
                                                    <th scope="col">
                                                        {{ $keywords['Serial_Number'] ?? __('Serial_Number') }} </th>
                                                    <th scope="col">{{ $keywords['Actions'] ?? __('Actions') }}</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($bcategorys as $key => $bcategory)
                                                    <tr>
                                                        <td>
                                                            <input type="checkbox" class="bulk-check"
                                                                data-val="{{ $bcategory->id }}">
                                                        </td>
                                                        <td>{{ $bcategory->name }}</td>
                                                        <td>
                                                            @if ($bcategory->status == 1)
                                                                <h2 class="d-inline-block"><span
                                                                        class="badge badge-success">{{ $keywords['Active'] ?? __('Active') }}</span>
                                                                </h2>
                                                            @else
                                                                <h2 class="d-inline-block"><span
                                                                        class="badge badge-danger">{{ $keywords['Deactive'] ?? __('Deactive') }}</span>
                                                                </h2>
                                                            @endif
                                                        </td>
                                                        <td>{{ $bcategory->serial_number }}</td>
                                                        <td>
                                                            <a class="btn btn-secondary btn-sm editbtn" href="#editModal"
                                                                data-toggle="modal"
                                                                data-bcategory_id="{{ $bcategory->id }}"
                                                                data-name="{{ $bcategory->name }}"
                                                                data-status="{{ $bcategory->status }}"
                                                                data-serial_number="{{ $bcategory->serial_number }}">
                                                                <span class="btn-label">
                                                                    <i class="fas fa-edit"></i>
                                                                </span>
                                                                {{ $keywords['Edit'] ?? __('Edit') }}
                                                            </a>
                                                            <form class="deleteform d-inline-block"
                                                                action="{{ route('user.blog.category.delete') }}"
                                                                method="post">
                                                                @csrf
                                                                <input type="hidden" name="bcategory_id"
                                                                    value="{{ $bcategory->id }}">
                                                                <button type="submit"
                                                                    class="btn btn-danger btn-sm deletebtn">
                                                                    <span class="btn-label">
                                                                        <i class="fas fa-trash"></i>
                                                                    </span>
                                                                    {{ $keywords['Delete'] ?? __('Delete') }}
                                                                </button>
                                                            </form>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>


    <!-- Create Blog Category Modal -->
    <div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">
                        {{ $keywords['Add_Blog_Category'] ?? __(' Add Blog Category') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="ajaxForm" class="modal-form create" action="{{ route('user.blog.category.store') }}"
                        method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="">{{ $keywords['Language'] ?? __('Language') }} **</label>
                            <select name="user_language_id" class="form-control">
                                <option value="" selected disabled>
                                    {{ $keywords['Select_a_language'] ?? __(' Select a language') }}</option>
                                @foreach ($userLanguages as $lang)
                                    <option value="{{ $lang->id }}">{{ $lang->name }}</option>
                                @endforeach
                            </select>
                            <p id="erruser_language_id" class="mb-0 text-danger em"></p>
                        </div>
                        <div class="form-group">
                            <label for="">{{ $keywords['Name'] ?? __('Name') }} **</label>
                            <input type="text" class="form-control" name="name" value=""
                                placeholder="{{ $keywords['Enter_Name'] ?? __('Enter name') }}">
                            <p id="errname" class="mb-0 text-danger em"></p>
                        </div>
                        <div class="form-group">
                            <label for="">{{ $keywords['Status'] ?? __('Status') }} **</label>
                            <select class="form-control ltr" name="status">
                                <option value="" selected disabled>
                                    {{ $keywords['Select_a_status'] ?? __('Select a status') }}</option>
                                <option value="1">{{ $keywords['Active'] ?? __('Active') }}</option>
                                <option value="0">{{ $keywords['Deactive'] ?? __('Deactive') }}</option>
                            </select>
                            <p id="errstatus" class="mb-0 text-danger em"></p>
                        </div>
                        <div class="form-group">
                            <label for="">{{ $keywords['Serial_Number'] ?? __('Serial Number') }} **</label>
                            <input type="number" class="form-control ltr" name="serial_number" value=""
                                placeholder="{{ $keywords['Enter_Serial_Number'] ?? __('Enter Serial Number') }}">
                            <p id="errserial_number" class="mb-0 text-danger em"></p>
                            <p class="text-warning">
                                <small>{{ $keywords['blog_category_Serial_Number_msg'] ?? __('The higher the serial number is, the later the blog category  will be shown') }}.</small>
                            </p>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary"
                        data-dismiss="modal">{{ $keywords['Close'] ?? __('Close') }}</button>
                    <button id="" data-form="ajaxForm" type="button"
                        class="submitBtn btn btn-primary">{{ $keywords['Submit'] ?? __('Submit') }}</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Blog Category Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">
                        {{ $keywords['Edit_Blog_Category'] ?? __('Edit Blog Category') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="ajaxEditForm" class="" action="{{ route('user.blog.category.update') }}"
                        method="POST">
                        @csrf
                        <input id="inbcategory_id" type="hidden" name="bcategory_id" value="">
                        <div class="form-group">
                            <label for="">{{ $keywords['Name'] ?? __('Name') }} **</label>
                            <input id="inname" type="name" class="form-control" name="name" value=""
                                placeholder="{{ $keywords['Enter_Name'] ?? __('Enter name') }}">
                            <p id="eerrname" class="mb-0 text-danger em"></p>
                        </div>
                        <div class="form-group">
                            <label for="">{{ $keywords['Status'] ?? __('Status') }} **</label>
                            <select id="instatus" class="form-control ltr" name="status">
                                <option value="" selected disabled>
                                    {{ $keywords['Select_a_status'] ?? __('Select a status') }}</option>
                                <option value="1">{{ $keywords['Active'] ?? __('Active') }}</option>
                                <option value="0">{{ $keywords['Deactive'] ?? __('Deactive') }}</option>
                            </select>
                            <p id="eerrstatus" class="mb-0 text-danger em"></p>
                        </div>
                        <div class="form-group">
                            <label for="">{{ $keywords['Serial_Number'] ?? __('Serial Number') }} **</label>
                            <input id="inserial_number" type="number" class="form-control ltr" name="serial_number"
                                value=""
                                placeholder="{{ $keywords['Enter_Serial_Number'] ?? __('Enter Serial Number') }}">
                            <p id="eerrserial_number" class="mb-0 text-danger em"></p>
                            <p class="text-warning">
                                <small>{{ $keywords['blog_category_Serial_Number_msg'] ??
                                    __('The higher the serial number is, the later the blog category
                                                                                                                              will be shown') }}.</small>
                            </p>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary"
                        data-dismiss="modal">{{ $keywords['Close'] ?? __('Close') }}</button>
                    <button id="updateBtn" type="button"
                        class="btn btn-primary">{{ $keywords['Update'] ?? __('Update') }}</button>
                </div>
            </div>
        </div>
    </div>
@endsection
