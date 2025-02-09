function redirigir(){
    window.location.href = "../../SFR/index.php"
    return false
}

const miniBarra = document.querySelector(".barraLateral")
const home = document.getElementById("iconoHome")

home.addEventListener("click",()=>{
    miniBarra.classList.toggle("miniBarra")
})