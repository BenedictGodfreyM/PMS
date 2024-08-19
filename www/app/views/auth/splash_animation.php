<div id="splash" class="d-none">
    <div id="splash-img" class="d-none"></div>
</div>

<script type="text/javascript">
    try {
        var splashScreen = document.getElementById('splash');
        var animatedIcon = document.getElementById('splash-img');
        var animatedElement = document.getElementById('animate-splash');
        animatedElement.classList.remove('animate');
        if (localStorage.getItem('isSplashAnimated') == null){
            localStorage.setItem('isSplashAnimated', true);
            animatedIcon.classList.remove('d-none');
            splashScreen.classList.remove('d-none');
            animatedElement.classList.remove('d-none');
            animatedElement.classList.add('animate');
        }else{
            animatedElement.classList.remove('d-none');
        }
    } catch (error) {
        console.error(error.message);
    }
</script>