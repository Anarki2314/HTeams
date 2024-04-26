<template>
    <header-view/>
    <div class="main-bg-container main-right-bg-container">

        <img :src=" '/assets/img/main-right-bg.png'" alt="" class="main-right-bg">
    </div>

    <div class="main-bg-container main-left-bg-container">
    <img :src=" '/assets/img/main-left-bg.png'" alt="" class="main-left-bg">

    </div>
        
    <section class="event-main-section">
        <div class="container-block">
            <div class="container-event">
                <div class="container-back">
                        <router-link to="/events" class="">Назад</router-link>
                    </div>
                <div class="container-event-main d-flex justify-content-center justify-content-md-start flex-wrap">

                    <div class="container-event-image">
                        <img :src="event.image" alt="" class="event-image">
                    </div>

                    <div class="container-event-info d-flex flex-column  justify-content-center">
                        <h3 class="event-title">{{event.title}}</h3>
                        <div class="container-event-tags d-flex align-items-center flex-wrap">

<span class="event-tag" v-for="tag, index in event.tags" :key="tag "> #{{ tag.replace(' ', '') }} </span>

</div>
                        <event-conditional-button :event="event"/>
                </div>

                </div>
            </div>
        </div>
    </section>

    <section class="event-info-section" id="info">

        <div class="container-block">
            <div class="container-about d-flex align-items-center justify-content-center justify-content-lg-between flex-wrap">
                <div class="container-info" v-if="event.status != 'Завершено'">
                    <h3 class="block-title text-center">Даты проведения</h3>

                    <div class="container-info-items d-flex flex-column align-items-center justify-content-center justify-content-md-between">
                        <div class="container-info-item">
                            <div class="info-item-text">Начало регистрации</div>
                            <div class="info-item info-first">{{ event.dates.registration }}</div>
                        </div>
                        <div class="container-info-item">
                            <div class="info-item-text">Начало события</div>
                            <div class="info-item info-second">{{ event.dates.start }}</div>
                        </div>
                        <div class="container-info-item">
                            <div class="info-item-text">Конец события</div>
                            <div class="info-item info-third">{{ event.dates.end }}</div>
                        </div>
                    </div>
                </div>

                <div class="container-winner" v-if="event.status == 'Завершено'">
                    <h3 class="block-title text-center">Победители</h3>

                    <div class="container-info-items d-flex flex-column align-items-center justify-content-center justify-content-lg-between">
                        <div class="container-info-item">
                            <div class="info-item-text">Первое место</div>
                            <div class="info-item info-first">{{ event.winners.first }}</div>
                        </div>
                        <div class="container-info-item">
                            <div class="info-item-text">Второе место</div>
                            <div class="info-item info-second">{{ event.winners.second }}</div>
                        </div>
                        <div class="container-info-item">
                            <div class="info-item-text">Третье место</div>
                            <div class="info-item info-third">{{ event.winners.third }}</div>
                        </div>
                    </div>
                </div>

                <div class="container-prize">
                    <h3 class="block-title text-center">Награда</h3>
                    <div class="container-prize-info d-flex align-items-center ">
                        <div class="container-prize-image">
                            <img src="https://via.placeholder.com/145" alt="" class="prize-image">
                        </div>

                        <div class="container-prize-text">1000000 руб</div>
                    </div>
                </div>
            </div>
        </div>  

    </section>


    <section class="event-description-section">
        
        <div class="container-block">
            <div class="container-description">
                <h3 class="block-title text-center">Описание</h3>
                <div class="container-description-text text-center">
                    <p>
                        Lorem ipsum dolor sit amet consectetur adipisicing elit. Provident porro velit iure saepe magnam soluta corrupti eum, accusantium aliquid! Quasi quisquam quidem amet placeat perspiciatis voluptatum et dolorem unde nulla nobis incidunt facilis, ut eius fuga nostrum. Eos perferendis iste aliquam corporis sunt laborum maiores nisi harum. Esse asperiores at veniam ex voluptate? Quod eveniet itaque, quae est velit eum totam sapiente voluptates debitis iste ipsum perspiciatis, quasi saepe, fugit beatae illum neque pariatur? Nulla, dolor cupiditate expedita porro assumenda praesentium sint tempore minima distinctio rerum harum asperiores et? Eligendi sed dolore repellat! Ratione nulla aspernatur architecto laborum excepturi quaerat rerum reprehenderit totam neque, in necessitatibus maxime beatae, saepe consectetur vitae nihil, tenetur fugit corrupti? Consequuntur culpa dolorum voluptatem. Iste alias quisquam nobis porro ab sint ea, explicabo asperiores reprehenderit ullam, beatae nihil architecto nam magnam voluptas assumenda, dicta accusamus temporibus? Saepe, iure autem voluptatum nemo perspiciatis, laudantium quasi earum dolorem cum tempora sit nobis dignissimos id sequi eum, harum libero blanditiis optio? Earum saepe quis iure consequuntur asperiores provident, est corrupti perspiciatis exercitationem explicabo voluptate assumenda pariatur! Nostrum, saepe itaque. Ab recusandae nesciunt odio suscipit labore sequi nam quas, ducimus adipisci distinctio perferendis fugit laboriosam iste sit in dolorem?
                    </p>
                </div>
                <div class="container-event-participate d-flex justify-content-center">
                            <div class="button-view main-button text-uppercase" v-if=" !isOrganizer && !event.isParticipated && event.status === 'Регистрация'">Принять участие</div>
                        </div>
            </div>
        </div>
    </section>
</template>

<script>

import HeaderView from '@/components/HeaderView.vue';
import EventConditionalButton from '../../components/event/EventConditionalButton.vue';
export default {
    components: {
        HeaderView,
        EventConditionalButton
    },
    data() {
        return {
            // TODO: remove isOrganizer from data and use store
            isOrganizer: false, 
            event: {
                id: 1,
                title: 'Название мероприятия',
                image: 'https://via.placeholder.com/900x500',
                tags:[
                    'asd',
                    'asd',
                    'asd',
                    'asd',
                    'asd',
                    'asd',
                    'asd',
                    'asd',
                    'asd',
                ],
                description: 'Описание мероприятия',
                dates:{
                    registration: '01.01.2022',
                    start: '01.01.2022',
                    end: '01.01.2022',
                },
                winners: {
                    'first': 'team1',
                    'second': 'team2',
                    'third': 'team3',
                },
                prize: 1000000,
                isParticipated: false,
                status: 'Регистрация',
            }
        }
    }
}
</script>

<style scoped>

section{
    min-height: 100dvh;
    display:flex;
    justify-content: center;
    align-items: center;
    /* margin: 8dvw auto; */
}

.event-main-section{
    margin: 5dvw auto 0;
    align-items: start;
    min-height: calc(92dvh - 140px);
}
.event-description-section {
    margin: 10dvh auto;
}


.main-bg-container{
    position: absolute;
    z-index: -1;
}

.main-bg-container img {
    transform: translateY(-50%);
}

.main-right-bg-container{
    right: 0;
    top: 100dvh;
}
.main-left-bg-container{
    left: 0;
    top: 200dvh;
}

.container-event{
    width: clamp( 280px , 95% , 1300px);
    margin: 0 auto;
}

.container-event-main {
    gap: 30px;
    

}



.event-image {
    width: clamp( 280px, 100% ,900px);
    aspect-ratio: 9/5;
}

.container-event-info{
    gap: clamp( 15px, 3vw , 30px);

    flex: 1;
}

.event-title {
    font-size: var(--size-title);
}

.container-event-tags{
    gap: 20px;
    }
.event-tag{
    color: var(--color-main);
    font-size: var(--size-text);
}




.container-about {
    gap: clamp( 15px, 3vw , 30px);
}

.container-info-items{
    gap: clamp( 15px, 3vw , 30px);
}

.container-info-item{
    display:grid;
    grid-template-columns: 1fr 1fr;
    gap: clamp( 25px, 3vw , 75px);
    align-items: center


}

.info-item-text{
    font-size: clamp( 14px , 3vw , 30px );
    text-align: end;
}

.info-item{
    text-align: start;
    color: var(--color-main);
}

.info-first{
    font-size: clamp( 24px , 4vw , 68px );
}

.info-second{
    font-size: clamp( 20px , 3.5vw , 60px );
}

.info-third{
    font-size: clamp( 16px , 3vw , 52px );
}


.container-prize-info{
    gap: clamp( 30px, 6vw , 60px);
}

.container-prize-image{
    width: clamp(   64px, 12vw , 145px);
    aspect-ratio: 1;

}

.container-prize-text{
    font-size: clamp( 24px , 5vw, 68px );
}



.container-description-text{
    font-size: clamp( 16px , 3vw , 30px );
    margin-bottom: clamp( 15px , 3vw , 30px );
}

.event-description-section .main-button{
    font-size: var(--size-title);
    padding: clamp( 10px , 2vw, 30px) clamp( 20px , 3vw,60px);
}
</style>