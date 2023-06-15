<template>
    <div id="search">
        <form action="/books" method="get">
            <input type="text" name="title" id="text" placeholder="フリーワードで検索">
            <label id="rental_label" for="rental_btn"><input type="checkbox"  id ="rental_btn" v-model="onlyRentable" v-on:change="change">レンタル可のみ</label>
        </form>
    </div>
    <div id="results">
        <div v-for="(item,index) in booksState" :key="index">
            <a  v-if="onlyRentable && item.rental.rental || onlyRentable == false " :href='`./books/${item.book.isbn}`'>
                <div class="result">
                    <img :src="item.book.largeImageUrl" alt="蔵書サムネイル">
                    <ul>
                        <li>{{ item.book.title }}</li>
                        <li>{{ item.book.author }}</li>
                        <li><span class="rating" :data-rate="Number.parseFloat(item.book.reviewAverage).toFixed(1)">
                        </span>({{(parseFloat(item.book.reviewAverage).toFixed(1))}}){{ item.book.booksGenreName }}</li>
                    </ul>
                    <div class="tf">
                        <img v-if="item.rental.rental" src="../../images/true.png" alt="可能">
                        <img v-else src="../../images/false.png" alt="不可">
                    </div>
                </div>
            </a>
        </div>
    </div>
</template>

<script>

    export default {
        props: {
            booksState: {
                type: Array,
                default: () => ''
            },
        },
        data(){
            return{onlyRentable: false}
        }, 
    }


</script>


<style scoped>
ul{
    padding: 0px;
    margin: 0px;
    list-style: none;
}
body {
    font-family: 'Nunito', sans-serif;
}
#search{
    z-index: 10;
    background-color: white;
    position: fixed;
    top: 64px;
    width: 100%;
    height: 92px;
}

a{
    color: black;
    text-decoration: none;
}
label#rental_label{
    display:inline-block;
    width: 114px;
    font-size: 14px;
    margin: 0px 20px 8px 20px;
}
#search input#text{
    margin: 8px 20px 8px 20px;
    height: 42px;
    width:calc(100% - 40px);
    border: 1px solid #d3d3d3;
    border-radius: 5px;
    font-size: 14px;
    padding-left: 20px;
}

#results{
    margin-top: 88px;
}

.result{
    border-bottom: 1px solid #d3d3d3;
    margin: 0px 20px 0px 20px;
    height: 97px;
    position: relative;
    padding-top: 8px;
    padding-bottom: 8px;
    display: flex;
}

#results a{
    text-decoration: none;
    color: black;
}
#results img{
    width: 64px;
    height: 81px;
}
#results ul{
    height: 100%;
    display:flex;
    flex-flow: column;
    justify-content: space-between;
}
#results ul li{
    line-height: 14px;
    font-size: 12px;
    padding-left: 40px;
}
#results ul li:first-child{
    width: 270px;
    white-space:nowrap;
    overflow:hidden;
    text-overflow:ellipsis;
    padding: 8px 0px 8px 32px;
    font-size: 14px;
    font-weight: bold;
}
#results ul li:last-child{
    width: 238px;
    line-height: 14px;
    font-size: 12px;
    padding-left: 40px;
    white-space:nowrap;
    overflow:hidden;
    text-overflow:ellipsis;
}

#results .tf img{
    position: absolute;
    width: 39px;
    height: 18px;
    right: 0px;
    bottom: 8px;
    text-align: right;
}

.rating{
    position: relative;
    z-index: 0;
    display: inline-block;
    white-space: nowrap;
    color: #d9d9d9;
}

.rating:before, .rating:after{
    content: '★★★★★';
}

.rating:after{
    position: absolute;
    z-index: 1;
    top: 0;
    left: 0;
    overflow: hidden;
    white-space: nowrap;
    color: #ffcf32;
}



.rating[data-rate="0.0"]:after{ width: 0%; }
.rating[data-rate="0.1"]:after{ width: 2%; }
.rating[data-rate="0.2"]:after{ width: 4%; }
.rating[data-rate="0.3"]:after{ width: 6%; }
.rating[data-rate="0.4"]:after{ width: 8%; }
.rating[data-rate="0.5"]:after{ width: 10%; }
.rating[data-rate="0.6"]:after{ width: 12%; }
.rating[data-rate="0.7"]:after{ width: 14%; }
.rating[data-rate="0.8"]:after{ width: 16%; }
.rating[data-rate="0.9"]:after{ width: 18%; }
.rating[data-rate="1.0"]:after{ width: 20%; }
.rating[data-rate="1.1"]:after{ width: 22%; }
.rating[data-rate="1.2"]:after{ width: 24%; }
.rating[data-rate="1.3"]:after{ width: 26%; }
.rating[data-rate="1.4"]:after{ width: 28%; }
.rating[data-rate="1.5"]:after{ width: 30%; }
.rating[data-rate="1.6"]:after{ width: 32%; }
.rating[data-rate="1.7"]:after{ width: 34%; }
.rating[data-rate="1.8"]:after{ width: 36%; }
.rating[data-rate="1.9"]:after{ width: 38%; }
.rating[data-rate="2.0"]:after{ width: 40%; }
.rating[data-rate="2.1"]:after{ width: 42%; }
.rating[data-rate="2.2"]:after{ width: 44%; }
.rating[data-rate="2.3"]:after{ width: 46%; }
.rating[data-rate="2.4"]:after{ width: 48%; }
.rating[data-rate="2.5"]:after{ width: 50%; }
.rating[data-rate="2.6"]:after{ width: 52%; }
.rating[data-rate="2.7"]:after{ width: 54%; }
.rating[data-rate="2.8"]:after{ width: 56%; }
.rating[data-rate="2.9"]:after{ width: 58%; }
.rating[data-rate="3.0"]:after{ width: 60%; }
.rating[data-rate="3.1"]:after{ width: 62%; }
.rating[data-rate="3.2"]:after{ width: 64%; }
.rating[data-rate="3.3"]:after{ width: 66%; }
.rating[data-rate="3.4"]:after{ width: 68%; }
.rating[data-rate="3.5"]:after{ width: 70%; }
.rating[data-rate="3.6"]:after{ width: 72%; }
.rating[data-rate="3.7"]:after{ width: 74%; }
.rating[data-rate="3.8"]:after{ width: 76%; }
.rating[data-rate="3.9"]:after{ width: 78%; }
.rating[data-rate="4.0"]:after{ width: 80%; }
.rating[data-rate="4.1"]:after{ width: 82%; }
.rating[data-rate="4.2"]:after{ width: 84%; }
.rating[data-rate="4.3"]:after{ width: 86%; }
.rating[data-rate="4.4"]:after{ width: 88%; }
.rating[data-rate="4.5"]:after{ width: 90%; }
.rating[data-rate="4.6"]:after{ width: 92%; }
.rating[data-rate="4.7"]:after{ width: 94%; }
.rating[data-rate="4.8"]:after{ width: 96%; }
.rating[data-rate="4.9"]:after{ width: 98%;}
.rating[data-rate="5.0"]:after{ width: 100%; }
</style>
