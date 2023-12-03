<!DOCTYPE html>
<html lang="en" class="border-l">
<head>
    <meta charset="UTF-8">
    <link href="https://unpkg.com/tailwindcss@^2/dist/tailwind.min.css" rel="stylesheet">
    <title>Login</title>
    <style>
        * {
            margin:0;
            padding:0;
        }
        .input {
            transition: border 0.2s ease-in-out;
            min-width: 280px
        }
        .input:focus+.label,
        .input:active+.label,
        .input.filled+.label {
            font-size: .75rem;
            transition: all 0.2s ease-out;
            top: -0.3rem;
            color: #6b7280;
        }
        .label {
            transition: all 0.2s ease-out;
            top: 0.4rem;
            left: 0;
        }
    </style>
</head>
<body>
<div class="h-screen bg-white relative flex flex-col space-y-10 justify-center items-center">
    <div class="bg-white md:shadow-lg shadow-none rounded p-6 w-96" >
        <h1 class="text-3xl font-bold leading-normal" >Sign in</h1>
        <form class="space-y-10 mt-5" method="POST" action="{{ url("login") }}">
            @csrf

            <div class="mb-4 relative">
                <span class="block text-sm font-medium text-slate-700 mb-3 leading-tighter text-gray-500 text-base">Email</span>
                <input id="email" class="w-full rounded px-3 border border-gray-500 pt-5 pb-2 focus:outline-none input active:outline-none @error('email') border-red-500 @enderror" type="text" name="email" value="{{ old('email') }}" autofocus>
            </div>
            <div class="mb-4 relative">
                <span class="block text-sm font-medium text-slate-700 mb-3 leading-tighter text-gray-500 text-base">Password</span>
                <div class="relative flex items-center border border-gray-500 focus:ring focus:border-blue-500 rounded">
                    <input id="password" class="w-full rounded px-3 pt-5 outline-none pb-2 focus:outline-none active:outline-none input active:border-blue-500 @error('email') border-red-500 @enderror" name="password" type="password"/>
                    <a id="labelShow" class="text-sm font-bold text-blue-700 hover:bg-gray-200 rounded-full px-2 py-1 mr-1 leading-normal cursor-pointer" onclick="changeType()">show</a>
                </div>
            </div>
            <div class="-m-2">
                <a class="font-bold text-blue-700 hover:bg-gray-200 hover:underline hover:p-5 p-4 rounded-full" href="{{ url("forgot") }}">Forgot password?</a>
            </div>
            <button class="w-full text-center bg-blue-400 hover:bg-blue-900 rounded-full text-white py-3 font-medium" type="submit">Sign in</button>
        </form>
    </div>
    {{-- <p>Member Area<a class="text-blue-700 font-bold hover:bg-gray-200 hover:underline hover:p-5 p-2 rounded-full" href="{{ url("") }}">Home</a></p> --}}
</div>

<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    const sessionStatus = "{{ Session::has('status') }}"
    const sessionMessage = "{{ Session::get('status') }}"
    const sessionClass = "{{ Session::get('alert-class') }}"

    if (sessionStatus) {
        Swal.fire(
            sessionClass == "error" ? "Opps!" : "Success!",
            sessionMessage,
            sessionClass
        )
    }
    function changeType() {
        const pass = document.querySelector("#password")
        if(pass.type == "password") {
            pass.type = "text"
            document.querySelector("#pwdText").style.display = "none"
            document.querySelector("#labelShow").textContent = "hide"
        } else {
            pass.type = "password"
            document.querySelector("#pwdText").style.display = ""
            document.querySelector("#labelShow").textContent = "show"
        }
    }
</script>
</body>
</html>
