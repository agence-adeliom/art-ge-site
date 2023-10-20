import Chart from 'chart.js/auto';

const generateButton = document.querySelector('button') as HTMLButtonElement | null;
const offCanvas = document.querySelector('#offcanvas') as HTMLDivElement | null;

if (generateButton && offCanvas) {
    generateButton.addEventListener('click', () => {
        const thematiques = 15;
        const formData = new FormData();

        for (let i = 0; i < thematiques; i++) {
            const canvas = document.createElement('canvas');
            // @ts-ignore
            new Chart(canvas, {
                type: 'bar',
                data: {
                    labels: ['Red', 'Blue', 'Yellow', 'Green', 'Purple', 'Orange'],
                    datasets: [{
                        label: '# of Votes',
                        data: [12, 19, 3, 5, 2, 3],
                        borderWidth: 1
                    }]
                },
                options: {
                    animation: false,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    },
                    plugins: {
                        // @ts-ignore
                        customCanvasBackgroundColor: {
                            color: 'white',
                        }
                    },
                },
                plugins: [
                    {
                        id: 'customCanvasBackgroundColor',
                        beforeDraw: (chart, args, options) => {
                            const {ctx} = chart;
                            ctx.save();
                            ctx.globalCompositeOperation = 'destination-over';
                            ctx.fillStyle = options.color || '#99ffff';
                            ctx.fillRect(0, 0, chart.width, chart.height);
                            ctx.restore();
                        }
                    }
                ],
            });

            offCanvas.appendChild(canvas);
            setTimeout(() => {
                const imageRaw = canvas.toDataURL('image/jpeg');
                formData.set(`images[${i}]`, imageRaw);
            }, 1);
        }

        setTimeout(() => {
            const xhr = new XMLHttpRequest();
            xhr.open('POST', '/save-images', true);
            xhr.setRequestHeader("X-Requested-With", 'XMLHttpRequest');
            xhr.onreadystatechange = () => {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    console.log(xhr);
                }
            };
            xhr.send(formData);
        }, thematiques + 1);
    });
}
