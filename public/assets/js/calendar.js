window.onload = () => {
    let calendarElt = document.querySelector("#calendrier")

    let calendar = new FullCalendar.Calendar(calendarElt, {
        initialView: 'timeGridWeek',
        locale: 'fr',
        timeZone: 'Europe/Paris',
        headerToolbar: {
           start: 'prev, next today',
           center: 'title',
           end: 'dayGridMonth, timeGridWeek' ,
       
        },
        //probleme secu avec raw?
        
              
})

calendar.render()
}