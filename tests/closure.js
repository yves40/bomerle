

console.log('Closure.js 1.00, Oct 14 2023');


let x= 1;

const parentFunction = () =>  {
    let parentval = 2;
    console.log('----------- parentFunction()');
    console.log(`x : ${x}`);
    console.log(`parentval : ${parentval}`);

    const childFunction = () => {
        console.log('----------- childFunction()');
        console.log(`x + 5 : ${x += 5}`);
        console.log(`parentval + 1 : ${parentval += 1}`);
    }
    return childFunction;
}
//
const result = parentFunction();
result();
result();
result();
console.log(x);
