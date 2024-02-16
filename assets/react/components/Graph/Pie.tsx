import React from "react";
import { Text } from "@components/Typography/Text";
import { Icon } from "@components/Typography/Icon";
import  Link  from "@components/Action/Link/Link";

const cleanPercentage = (percentage : number) => {
  const tooLow = !Number.isFinite(+percentage) || percentage < 0;
  const tooHigh = percentage > 100;
  return tooLow ? 0 : tooHigh ? 100 : +percentage;
};

const Circle = ({ color, pct } : {
    color: string,
    pct: number
}) => {
  const r = 90;
  const circ = 2 * Math.PI * r;
  const strokePct = ((100 - pct) * circ) / 100;
  return (
    <circle
      r={r}
      cx={100}
      cy={100}
      fill="transparent"
      stroke={strokePct !== circ ? color : ""} // remove colour as 0% sets full circumference
      strokeWidth={"12px"}
      strokeDasharray={circ}
      strokeDashoffset={pct ? strokePct : 0}
      strokeLinecap="butt"
      className="animation-progress"
    ></circle>
  );
};

const Content = ({ percentage, color } : {
    percentage: number, 
    color: string
}) => {
  return (
    <div className={`flex flex-col w-[76%] rounded-full aspect-square absolute top-1/2 left-1/2 justify-center items-center -translate-x-1/2 -translate-y-1/2`} style={{background: color}}>
        <Text className="font-title" color="white" size={'6xl'}>{percentage.toFixed(0)}</Text>
        <Text className="font-title" color="white" size={'3xl'}>/100</Text>
    </div>
  );
};

const Pie = ({ percentage, color, type, icon} : {
    percentage: number,
    color: string,
    type: string,
    icon: string
}) => {

  let scrollToBlock = document.getElementById(`${type}-analysis`)!;
  const handleScroll = () => {
    scrollToBlock.scrollIntoView({ behavior: 'smooth' });
  }

  

  const pct = cleanPercentage(percentage);
  return (
    <div className="w-fit lg:w-1/3 lg:flex lg:flex-col lg:items-center">
        <div className="w-fit relative">
          <svg width={200} height={200}>
          <g transform={`rotate(-90 ${"100 100"})`}>
          <Circle color="#E4E4E7" pct={100}/>
          <Circle color={color} pct={pct} />
          </g>
          </svg>
          <Content percentage={pct} color={color} />
      </div>
      <div className="text-center mt-6">
          <span style={{color: color}}><Icon icon={`fa-light ${icon}`}  size={'4xl'}></Icon></span>
          <Text style={{color: color}} size='2xl' className={'font-title'}>{type}</Text>
          <div className="mt-2 flex justify-center">
              <Link
                    label="Le score en détail"
                    icon="fa-chevron-down"
                    onClickFunction={() =>handleScroll()}/>
          </div>
      </div>
    </div>
    
    
  );
};

export default Pie;
